<?php

// Including classes and functions

require_once '../code/php/classes.php';
require_once '../code/php/helpers.php';

// Html to Pdf

use Spipu\Html2Pdf\Html2Pdf;
$html2pdf = new Html2Pdf();

// Connecting to the database

$db = new Db();
$pdo = $db->connect();
if (is_string($pdo)) {
     newLog('Error interno', $pdo, 4);
}

$bot = new Bot();
$msg = $bot->newMsg();

// Permission checks

if ($bot->get('chatType') == 'private') {
     // Prevent anyone to chat privatly with the bot
     if (!$bot->isAdmin()) {
          $bot->sendMessage('El uso del bot no está permitido de manera individual');
          die();
     }
     // Set the admin var to true
     $admin = true;
} else {
     // Set the admin var to false
     $admin = false;
     // Prevent anyone to attach the bot to an unauthorized group
     if (!$bot->isGroup()) {
          $bot->sendMessage('El uso del bot no está autorizado en este grupo'."\n".'Este evento <b>ha quedado registrado</b>');
          $log = array(
               'TEXT' => 'Se ha intentado usar el bot con otro grupo no autorizado',
               'DATA' => $bot->get('all')
          );
          newLog($log,'Evento de Seguridad del Bot de Telegram',3);
          die();
     }
     // Prevent anyone to use a bot to DDOS the system
     if ($bot->get('isBot')) {
          $bot->sendMessage('El uso del bot no está autorizado a través de otros bots'."\n".'Este evento <b>ha quedado registrado</b>');
          $log = array(
               'TEXT' => 'Se ha intentado usar el bot con otro bot',
               'DATA' => $bot->get('all')
          );
          newLog($log,'Evento de Seguridad del Bot de Telegram',3);
          die();
     }
     // Accept or reject a user depending to the config file
     if ($bot->get('listMode') == 'white') {
          // Only accept users on the list
          if (!in_array($bot->get('userId'),$bot->get('listUsers'))) {
               $bot->sendMessage('Usted no está autorizado a utilizar esta herramienta'."\n".'Este evento <b>ha quedado registrado</b>');
               $log = array(
                    'TEXT' => 'Una persona no autorizada ha intentado utilizar el bot',
                    'DATA' => $bot->get('all')
               );
               newLog($log,'Evento de Seguridad del Bot de Telegram',3);
               die();
          }
     }
     if ($bot->get('listMode') == 'black') {
          // Reject users on the blacklist
          if (in_array($bot->get('userId'),$bot->get('listUsers'))) {
               $bot->sendMessage('Usted no está autorizado a utilizar esta herramienta'."\n".'Este evento <b>ha quedado registrado</b>');
               $log = array(
                    'TEXT' => 'Una persona no autorizada ha intentado utilizar el bot',
                    'DATA' => $bot->get('all')
               );
               newLog($log,'Evento de Seguridad del Bot de Telegram',3);
               die();
          }
     }
}

// Search processing

if (substr($msg,0,5) == 'busca') {
     $raw_command = substr($msg,5,strlen($msg));
     $arguments = explode('@',$raw_command);
     $bot->sendMessage($arguments[1]);
     $bot->sendMessage($arguments[2]);
     $bot->sendMessage($arguments[3]);
     die();

     if (isset($arguments[3])) {
          // Filter by type,brand and model
          $arr = $db->search($pdo,$arguments[1],$arguments[2],$arguments[3]);
     }

     if (!is_array($arr)) {
          $bot->sendMessage('Hubo un error interno, contacte con el Administrador');
          newLog($types,'Error en el comando buscar',4);
          break;
     }
     if (count($arr) == 0) {
          $bot->sendMessage('No se ha encontrado ningún dispositivo');
          break;
     }
     if (count($arr)>0) {
          $table = '<table border="1">';
          $table .= `
                         <tr>
                              <td>TIPO</td>
                              <td>MARCA</td>
                              <td>MODELO</td>
                              <td>UBICACIÓN</td>
                              <td>ACTUALIZADO</td>
                         </tr>
          `;
          foreach ($arr as $eq) {
               $table .= '<tr>';
                    $table .= '<td>' . $eq[''] . '</td>';
               $table .= '</tr>';
          }
          $table .= '</table>';

          $html2pdf->writeHTML($table);
          $filename = date('Y_m_d_H_i_s');
          $pdf_raw  = $html2pdf->output($filename, 'S');
          $pdf_path = __DIR__ . '/files/docs/'.$filename.'.pdf';
          file_put_contents($pdf_path,$pdf_raw);
          $bot->sendDocument($pdf_path);
     }
     die();
}

// Normal Command processing

switch ($msg) {
     // Normal commands
     case 'equipos':
          // Get types of equipment and create a table
          $types = $db->getTypes($pdo);
          if (!is_array($types)) {
               $bot->sendMessage('Hubo un error interno, contacte con el Administrador');
               newLog($types,'Error en el comando equipos',4);
               break;
          }
          if (count($types) == 0) {
               $bot->sendMessage('No existen tipos de equipamiento');
               break;
          }
          if (count($types) > 0) {
               $resp = "<b>Tipos de equipamiento:</b>\n";
               foreach ($types as $type) {
                    $resp .= $type['TYPE'] . "\n";
               }
          }

          $bot->sendMessage($resp);
          break;

     // Admin commands
     case '/debug':
          if ($admin) {
               $bot->sendMessage(date('Y-m-d H:i:s'));
          } else {
               $bot->sendMessage('No conozco ese comando');
          }
          break;
     case '/doc':
          $bot->sendDocument('test.pdf');
          break;

     case '/img':
          $bot->sendImage('t2.jpeg');
          break;

     // Default response
     default:
          $bot->sendMessage('No conozco ese comando');
          break;

}


?>
