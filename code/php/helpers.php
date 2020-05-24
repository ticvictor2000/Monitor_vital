<?php

function newLog($msg,$type,$warn) {
     // Load log file
     $log_arr = json_decode(file_get_contents(dirname(__DIR__,2) . '/code/json/log.json'),true);
     if (!is_array($log_arr)) {
          return false;
     }

     // Create new log
     $log_arr['Logs']['Log_' . date('d-m-y_h-i-s')] = array(
          'FECHA' => date('d/m/y'),
          'HORA' => date('H:i:s'),
          'TIPO' => $type,
          'MENSAJE' => $msg,
          'GRAVEDAD' => $warn,
          'DEBUG' => $_SERVER
     );

     // beautify and write json

     $data = json_encode($log_arr, JSON_PRETTY_PRINT);

     // Write new data to log
     $log_file = fopen(dirname(__DIR__,2) . '/code/json/log.json', 'w');
     if (!$log_file) {
          return false;
     }
     if (!fwrite($log_file, $data)) {
          return false;
     }
     // If there are no errors return true
     return true;
}

function auth($elevation) {
     if (!isset($_SESSION)) {
          session_start();
     }

     if ($elevation == 'tech') {
          if ($_SESSION['user']['ROLE'] != 'tech' && $_SESSION['user']['ROLE'] != 'admin') {
               $_SESSION['errors']['auth'] = 'No tienes acceso a esta sección de la aplicación';
               newLog('El usuario ' . $_SESSION['user']['USERNAME'] . ' ha intentado acceder a una sección que no le corresponde', 'Acceso Restringido',2);
               header('Location: index.php');
               return false;
          }
     }

     if ($elevation == 'admin') {
          if ($_SESSION['user']['ROLE'] != 'admin') {
               $_SESSION['errors']['auth'] = 'No tienes acceso a esta sección de la aplicación';
               newLog('El usuario ' . $_SESSION['user']['USERNAME'] . ' ha intentado acceder a una sección que no le corresponde', 'Acceso Restringido',2);
               header('Location: index.php');
               return false;
          }
     }

     unset($_SESSION['errors']['auth']);
     return true;
}

function macCiscoToStd($oldmac) {
     $mac_w_dots = explode('.',$oldmac)[0] . explode('.',$oldmac)[1] . explode('.',$oldmac)[2];
     $mac_w_dots_uppercase = strtoupper($mac_w_dots);
     $mac_std = substr($mac_w_dots_uppercase,0,2) . ':' . substr($mac_w_dots_uppercase,2,2) . ':';
     $mac_std .= substr($mac_w_dots_uppercase,4,2) . ':' . substr($mac_w_dots_uppercase,6,2) . ':';
     $mac_std .= substr($mac_w_dots_uppercase,8,2) . ':' . substr($mac_w_dots_uppercase,10,2);
     return $mac_std;
}

function searchMacDb($id, $array) {
   foreach ($array as $key => $val) {
       if ($val['MACEQ'] === $id) {
           return true;
       }
   }
   return false;
}

function geterrs() {
     $logs = json_decode(file_get_contents(dirname(__DIR__,2) . '/code/json/log.json'),true)['Logs'];
     $logs_return = array();
     $logs_return_i = 0;
     foreach ($logs as $log) {
          if ($log['GRAVEDAD']==1) {
               if (!is_array($log['MENSAJE'])) {
                    $logs_return[$logs_return_i] = $log;
                    $logs_return_i++;
               }
          }
     }
     return $logs_return;
}


/* COMMANDS CISCO */
/*
     show ip arp (show devices connected)
*/
?>
