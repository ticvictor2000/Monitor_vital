<?php

// Requiring classes and helpers

require_once 'classes.php';
require_once 'helpers.php';

// Connection to the Database

$db = new Db();
$pdo = $db->connect();

// Use session
if (!isset($_SESSION)) {
     session_start();
}

// Pre-loading the field values

$type = trim($_POST['type']);
$ip = trim($_POST['ip']);
$telnet = trim($_POST['telnet']);
$ssh = trim($_POST['ssh']);
$nports = trim($_POST['nports']);
$brand = trim($_POST['brand']);
$model = trim($_POST['model']);
$pass = trim($_POST['pass']);

// Validating fields

if ($type != 'switch' && $type != 'ap') {
     echo '<strong>Introduce un tipo de dispositivo válido</strong>';
     if ($type != 'null') {
          newLog($_SESSION['user']['NAME'] . ' ha intentado modificar el select con un valor no válido [' . $type . ']','Alerta de Seguridad',3);
     }
     die();
}

if (!filter_var($ip, FILTER_VALIDATE_IP)) {
     echo '<strong>Introduce una dirección IP válida</strong>';
     newLog('Error al añadir un nuevo dispositivo de red, IP errónea','Errores de formulario',1);
     die();
}

if ($telnet != '0' && $telnet != '1') {
     echo '<strong>Selecciona un valor válido para el soporte Telnet/SSH</strong>';
     if ($telnet != 'null') {
          newLog($_SESSION['user']['NAME'] . ' intentó modificar el valor del soporte Telnet con [' . $telnet . '] en el formulario de registro de equipamiento de red','Alerta de Seguridad',3);
     }
     die();
}

if ($ssh != 0 && $ssh != 1) {
     echo '<strong>Selecciona un valor válido para el soporte Telnet/SSH</strong>';
     if ($ssh != 'null') {
          newLog($_SESSION['user']['NAME'] . ' intentó modificar el valor del soporte SSH  con [' . $ssh . '] en el formulario de registro de equipamiento de red','Alerta de Seguridad',3);
     }
     die();
}

if (!is_numeric($nports)) {
     echo '<strong>El número de puertos no es un número</strong>';
     newLog($_SESSION['user']['NAME'] . ' intentó modificar el valor del número de puertos con [' . $nports . '] en el formulario de registro de equipamiento de red','Alerta de Seguridad',3);
     die();
}

if ($brand != 'cisco' && $brand != 'openwrt') {
     echo '<strong>Introduce una marca compatible</strong>';
     if ($brand != 'null') {
          newLog($_SESSION['user']['NAME'] . ' ha intentado modificar el select de la marca del formulario de registro de dispositivos de red con un valor no válido [' . $type . ']','Alerta de Seguridad',3);
     }
     die();
}



// Trying to connect to the Network Device

if (!$ssh) {
     try {
          $tcon = new Telnet($ip);
     } catch (Exception $e) {
          echo 'Hubo un problema al conectarse al dispositivo de red vía Telnet';
          newLog($e->getMessage(),'Formulario de registro de dispositivos de red',1);
          die();
     }
}

if ($ssh) {
     echo 'En desarrollo';
     die();
}

// Get MAC Addr of network device

// Check brand

if ($brand == 'cisco') {
     // Check connection protocol
     if (isset($tcon)) {
          // Create new device
          $cnd = new CiscoNetDeviceTelnet($tcon,$pass);
          // Authenticate
          if (!$cnd->login()) {
               echo 'Contraseña incorrecta';
               newLog('Autenticacion incorrecta','Autenticacion telnet dispositivo Cisco',1);
               die();
          }
          // Get mac address
          $mac = $cnd->getMac($tcon);
          // Add data to the network device
          $cnd->addData($mac,$type,$ip,$ssh,$telnet,$nports,$model);
          // Insert into database
          $insert_db = $cnd->intoDB($pdo);
          if (!$insert_db) {
               echo 'Error interno al agregar el dispositivo de red al sistema';
               newLog($insert_db,'Agregar dispositivos de red',4);
               die();
          } else {
               echo 'Dispositivo añadido correctamente';
          }
     }
     if (isset($sshcon)) {
          echo 'En desarrollo';
          die();
     }
     if (isset($tcon) && isset($sshcon)) {
          echo 'No se ha conectado al dispositivo de red correctamente';
          newLog('No se ha conectado al dispositivo de red correctamente','Registro de dispositivos de red',1);
          die();
     }
}

if ($brand == 'openwrt') {
     echo 'En desarrollo';
     die();
}

?>
