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

if ($act == 'change_gen_cnf') {
     // Get config
     $botToken = trim($_POST['botToken']);
     $admins = trim($_POST['admins']);
     $groupId = trim($_POST['groupId']);

     // Import the config file
     $cnf = json_decode(file_get_contents(dirname(__DIR__,2) . '/code/json/cnf.json'),true);
     if (!is_array($cnf)) {
          echo 'Hubo un error interno, contacte con el administrador';
          newLog('No se puede abrir el archivo de configuración','Error de ficheros',4);
          die();
     }
     $cnf['Telegram']['BotToken'] = $botToken;
     if (count(explode(', ',$admins))>1) {
          // Array
          $cnf['Telegram']['AdminId'] = explode(', ',$admins);
     } else {
          // String
          $cnf['Telegram']['AdminId'] = $admins;
     }

     $cnf['Telegram']['GroupId'] = $groupId;

     $cnf_file = fopen(dirname(__DIR__,2) . '/code/json/cnf.json','w');
     fwrite($cnf_file,json_encode($cnf,JSON_PRETTY_PRINT));
     fclose($cnf_file);

     echo 'Configuración cambiada correctamente';
}

if ($act == 'change_use_cnf') {
     // Get config
     $mode = trim($_POST['mode']);
     $users_list = trim($_POST['users_list']);

     // Check that the mode is in the authorized values
     if ($mode != 'white' && $mode != 'black') {
          echo 'Hubo un error interno, contacte con el administrador';
          newLog($_SESSION['user']['NAME'].' ha intentado poner un modo de Telegram inexistente','Alerta de seguridad',3);
          die();
     }

     // Import the config file
     $cnf = json_decode(file_get_contents(dirname(__DIR__,2) . '/code/json/cnf.json'),true);
     if (!is_array($cnf)) {
          echo 'Hubo un error interno, contacte con el administrador';
          newLog('No se puede abrir el archivo de configuración','Error de ficheros',4);
          die();
     }

     $cnf['Telegram']['List']['Mode'] = $mode;
     if (count(explode(', ',$users_list))>1) {
          // Array
          $cnf['Telegram']['List']['Users'] = explode(', ',$users_list);
     } else {
          // String
          $cnf['Telegram']['List']['Users'] = $users_list;
     }

     $cnf_file = fopen(dirname(__DIR__,2) . '/code/json/cnf.json','w');
     fwrite($cnf_file,json_encode($cnf,JSON_PRETTY_PRINT));
     fclose($cnf_file);

     echo 'Configuración cambiada correctamente';

}

if ($act == 'change_rte_cnf') {
     // Get config
     $docs_path = trim($_POST['docs_path']);
     $imgs_path = trim($_POST['imgs_path']);

     // Import the config file
     $cnf = json_decode(file_get_contents(dirname(__DIR__,2) . '/code/json/cnf.json'),true);
     if (!is_array($cnf)) {
          echo 'Hubo un error interno, contacte con el administrador';
          newLog('No se puede abrir el archivo de configuración','Error de ficheros',4);
          die();
     }

     $cnf['Telegram']['Files_dirs']['Docs'] = $docs_path;
     $cnf['Telegram']['Files_dirs']['Images'] = $imgs_path;

     $cnf_file = fopen(dirname(__DIR__,2) . '/code/json/cnf.json','w');
     fwrite($cnf_file,json_encode($cnf,JSON_PRETTY_PRINT));
     fclose($cnf_file);

     echo 'Configuración cambiada correctamente';
}


?>
