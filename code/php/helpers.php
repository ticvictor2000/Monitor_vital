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

function getColor() {
     return 'azure';
}

function getLog() {
     $logs = json_decode(file_get_contents(dirname(__DIR__,2) . '/code/json/log.json'),true)['Logs'];

     $table = '<table border="1">';
     $table .= '
                    <tr>
                         <td>FECHA</td>
                         <td>HORA</td>
                         <td>TIPO</td>
                         <td>MENSAJE</td>
                         <td>GRAVEDAD</td>
                    </tr>
     ';
     foreach ($logs as $log) {
          $table .= '<tr>';
               $table .= '<td>' . $log['FECHA'] . '</td>';
               $table .= '<td>' . $log['HORA'] . '</td>';
               $table .= '<td>' . $log['TIPO'] . '</td>';
               if (is_string($log['MENSAJE'])) {
                    $table .= '<td>' . $log['MENSAJE'] . '</td>';
               } else {
                    $table .= '<td>' . 'Ver panel de seguridad' . '</td>';
               }
               $table .= '<td>' . $log['GRAVEDAD'] . '</td>';
          $table .= '</tr>';
     }
     $table .= '</table>';
     return $table;
}

function getTelegramCnf($value) {
     $cnf_telegram = json_decode(file_get_contents(dirname(__DIR__,2) . '/code/json/cnf.json'),true)['Telegram'];
     switch ($value) {
          case 'token':
               return $cnf_telegram['BotToken'];
               break;
          case 'admins':
               if (is_array($cnf_telegram['AdminId'])) {
                    $listAdmins_str = '';
                    for ($i=0; $i < count($cnf_telegram['AdminId']); $i++) {
                         $listAdmins_str .= $cnf_telegram['AdminId'][$i];
                         if (count($cnf_telegram['AdminId'])-1 != $i) {
                              $listAdmins_str .= ', ';
                         }
                    }
                    return $listAdmins_str;
               } else {
                    return $cnf_telegram['AdminId'];
               }
               break;
          case 'groupid':
               return $cnf_telegram['GroupId'];
               break;
          case 'listMode':
               return $cnf_telegram['List']['Mode'];
               break;
          case 'listUsers':
               if (is_array($cnf_telegram['List']['Users'])) {
                    $listUsers_str = '';
                    for ($i=0; $i < count($cnf_telegram['List']['Users']); $i++) {
                         $listUsers_str .= $cnf_telegram['List']['Users'][$i];
                         if (count($cnf_telegram['List']['Users'])-1 != $i) {
                              $listUsers_str .= ', ';
                         }
                    }
                    return $listUsers_str;
               } else {
                    return $cnf_telegram['List']['Users'];
               }
               break;
          case 'docsDirs':
               return $cnf_telegram['Files_dirs']['Docs'];
               break;
          case 'imgsDirs':
               return $cnf_telegram['Files_dirs']['Images'];
               break;
     }
}

function getSecurity($raw=false) {
     $logs = json_decode(file_get_contents(dirname(__DIR__,2) . '/code/json/log.json'),true)['Logs'];
     if ($raw) {
          $logs_download = array();
          $logs_download_i = 0;
          foreach ($logs as $log) {
               if ($log['GRAVEDAD']==3) {
                    $logs_download[$logs_download_i] = $log;
                    $logs_download_i++;
               }
          }
          return json_encode($logs_download,JSON_PRETTY_PRINT);
     } else {
          $logs_return = array();
          $logs_return_i = 0;
          foreach ($logs as $log) {
               if ($log['GRAVEDAD']==3) {
                    if (!is_array($log['MENSAJE'])) {
                         $logs_return[$logs_return_i]['MESSAGE'] = $log['MENSAJE'];
                         $logs_return[$logs_return_i]['TYPE'] = $log['TIPO'];
                         $logs_return[$logs_return_i]['DATE'] = $log['FECHA'];
                         $logs_return[$logs_return_i]['TIME'] = $log['HORA'];
                         $logs_return[$logs_return_i]['AT_IP'] = $log['DEBUG']['REMOTE_ADDR'];
                         $logs_return[$logs_return_i]['PORT'] = $log['DEBUG']['SERVER_PORT'];
                         $logs_return[$logs_return_i]['SCRIPT'] = $log['DEBUG']['SCRIPT_FILENAME'];
                         $logs_return_i++;
                    }
               }
          }
          return $logs_return;
     }
}



function getLogs() {
     $logs = json_decode(file_get_contents(dirname(__DIR__,2) . '/code/json/log.json'),true)['Logs'];
     $logs_return = array();
     $logs_return_i = 0;
     foreach ($logs as $log) {
          if ($log['GRAVEDAD']!=3) {
               if (!is_array($log['MENSAJE'])) {
                    $logs_return[$logs_return_i]['MESSAGE'] = $log['MENSAJE'];
                    $logs_return[$logs_return_i]['TYPE'] = $log['TIPO'];
                    $logs_return[$logs_return_i]['CODE'] = $log['GRAVEDAD'];
                    $logs_return[$logs_return_i]['DATE'] = $log['FECHA'];
                    $logs_return[$logs_return_i]['TIME'] = $log['HORA'];
                    $logs_return[$logs_return_i]['AT_IP'] = $log['DEBUG']['REMOTE_ADDR'];
                    $logs_return[$logs_return_i]['PORT'] = $log['DEBUG']['SERVER_PORT'];
                    $logs_return[$logs_return_i]['SCRIPT'] = $log['DEBUG']['SCRIPT_FILENAME'];
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
