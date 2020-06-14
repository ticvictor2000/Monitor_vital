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

if ($act == 'req_medeq') {
     // Make query

     try {
          $medeq = $pdo->query("SELECT MACEQ,TYPE,BRAND,MODEL,LAST_SEEN FROM Medical_eq")->fetchAll(PDO::FETCH_ASSOC);
     } catch (PDOException $e) {
          echo '*Error al buscar el equipamiento médico';
          newLog($e->getMessage(), 'Formulario gestión del equipamiento médico',4);
          die();
     }

     // Return data to JS

     echo json_encode($medeq);
}

if ($act == 'act_medeq') {
     $data = json_decode(trim($_POST['json']),true);

     if (!is_array($data)) {
          echo '*Error interno, contacte con el administrador';
          newLog('Manipulación en el JSON de actualización','Alerta de Seguridad',3);
          die();
     } else {
          foreach ($data as $medeq) {
               // Get fields
               $maceq = trim($medeq[0]);
               $type = trim($medeq[1]);
               $brand = trim($medeq[2]);
               $model = trim($medeq[3]);
               // Update all fields with the DB
               try {
                    $upd_fields = $pdo->query("UPDATE Medical_eq SET TYPE='$type',BRAND='$brand',MODEL='$model' WHERE MACEQ='$maceq'");
               } catch (PDOException $e) {
                    echo '*Error interno, contacte con el administrador';
                    newLog($e->getMessage,'Actualizar equipamiento médico');
                    die();
               }
          }
     }
     echo 'Los datos de los dispositivos de red se han actualizado correctamente';
}

if ($act == 'get_types') {
     $db->getTypes($pdo);
}







?>
