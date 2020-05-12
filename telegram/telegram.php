<?php

// Including classes and functions

require_once '../code/php/classes.php';
require_once '../code/php/helpers.php';

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

// Command processing

switch ($msg) {
     // Normal commands
     case '/respiradores@MonitorVitalBot':
          $bot->sendMessage('Lista de respiradores');
          break;

     // Admin commands
     case '/debug':
          if ($admin) {
               $bot->sendMessage(date('Y-m-d H:i:s'));
          } else {
               $bot->sendMessage('No conozco ese comando');
          }
          break;

     default:
          $bot->sendMessage('No conozco ese comando');
          break;
}


?>
