<?php

// Requires
require_once 'classes.php';
require_once 'helpers.php';

// Connect to DB
$db = new Db();
$pdo = $db->connect();

// Session

if (!isset($_SESSION)) {
     session_start();
}

// Get POST Data

$act = trim($_POST['act']);

if ($act == 'get_dev') {
     $type = trim($_POST['type']);

     // Verify type
     if ($type != 'switch' && $type != 'ap') {
          echo 'Debes seleccionar un tipo válido';
          newLog($_SESSION['user']['NAME'] . ' ha intentado cambiar el tipo en el select en el formulario de gestión de puertos', 'Alerta de seguridad',3);
          die();
     }

     // Make query

     try {
          $get_netdevices = $pdo->query("SELECT MACND,BRAND,MODEL FROM Net_devices WHERE TYPE='$type'")->fetchAll(PDO::FETCH_ASSOC);
     } catch (PDOException $e) {
          echo 'Error al buscar los dispositivos de red';
          newLog($e, 'Formulario gestión de puertos',4);
          die();
     }

     // Return data to JS

     echo json_encode($get_netdevices);
}

if ($act == 'get_ports') {
     $mac = trim($_POST['mac']);

     if (!filter_var($mac, FILTER_VALIDATE_MAC)) {
          echo false;
          newLog($_SESSION['user']['USERNAME'] . ' ha modificado la mac al leer los puertos', 'Alerta de Seguridad',3);
          die();
     }

     try {
          $ssh = $pdo->query("SELECT SSH,IP_ADDR FROM Net_devices WHERE MACND='$mac'")->fetchAll(PDO::FETCH_ASSOC);
     } catch (PDOException $e) {
          echo false;
          newLog($e,'Fallo en la consulta de obtención de puertos del dispositivo de red [' . $mac . ']',4);
          die();
     }

     if ($ssh[0]['SSH']) {
          echo false;
          die();
     }


     if (!$ssh[0]['SSH']) {
          $tcon = new Telnet($ssh[0]['IP_ADDR']);
          $cnd = new CiscoNetDeviceTelnet($tcon, 'toor');
          $cnd->login();
          $ports = $cnd->getPorts($tcon);

          // Check if ports exists in the database, if not, we create then
          $ports_db = $db->getPorts($mac,$pdo);

          if (is_string($ports_db)) {
               echo 'Hubo un error interno';
               newLog($ports_db, 'Gestión de puertos, comprobar puertos en la DB',4);
               die();
          }

          if (count($ports_db) != count($ports)) {
               // Create new ports in the data
               newLog('Insertando nuevos puertos para el dispositivo de red [' . $mac . ']','Gestión puertos, no existen puertos iguales en la DB',1);
               if ($db->newPorts($mac,$ports,$pdo) != true) {
                    echo 'Hubo un error interno';
                    newLog($db->newPorts($mac,$ports,$pdo), 'Gestión de puertos, insertar puertos en la DB',4);
                    die();
               }
          }

          if (count($ports_db) == count($ports)) {
               // Display ports
               $html = '';
               for ($i=0; $i < count($ports_db); $i++) {
                    $html .= '<tr>';
                         $html .= '<td>' . $ports_db[$i]['NAME'] . '</td>';
                         $html .= '<td>';
                              $html .= '<input type="text" placeholder="Ubicación" pname="' . $ports_db[$i]['NAME'] . '" list="locations" value="' . $ports_db[$i]['LOCATION'] . '" />';
                         $html .= '</td>';
                    $html .= '</tr>';
               }
               echo $html;
               die();
          }
     }
}

if ($act == 'req_locations') {
     $locations = $db->getLocations($pdo);
     if (!is_array($locations)) {
          newLog($locations,'Obtener ubicaciones de la base de datos',3);
          die();
     }
     echo json_encode($locations);
     die();
}

if ($act == 'upd_ports') {
     $ports_location = json_decode(trim($_POST['pl']),true);
     $mac = trim($_POST['mac']);

     $ports_db = $db->getPorts($mac,$pdo);

     if (is_string($ports_db)) {
          echo false;
          newLog($ports_db, 'Gestión de puertos, actualizar puertos en la DB',4);
          die();
     }

     for ($i=0; $i < count($ports_db); $i++) {
          if ($ports_db[$i]['LOCATION'] != $ports_location[$i][0]) {
               $update_loc = $db->updatePorts($pdo,$ports_location[$i][0],$ports_location[$i][1],$mac);

               if (is_string($update_loc)) {
                    echo false;
                    newLog($update_loc,'Gestión de puertos, actualizar ubicaciones');
                    die();
               }

               echo true;
          }
     }
}






?>
