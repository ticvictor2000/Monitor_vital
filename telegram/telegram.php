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


if (!$bot->isGroup()) {
     $bot->sendMessage('El uso del bot no está autorizado en este grupo'."\n".'Este evento <b>ha quedado registrado</b>');
     $log = array(
          'TEXT' => 'Se ha intentado usar el bot con otro grupo no autorizado',
          'DATA' => $bot->get('all')
     );
     newLog($log,'Evento de Seguridad del Bot de Telegram',3);
     die();
}

if (!$bot->isGroup() || !$bot->isAdmin()) {
     $bot->sendMessage('El uso del bot no está permitido de manera individual');
     die();
}

switch ($msg) {
     case '/respiradores@MonitorVitalBot':
          $bot->sendMessage('Lista de respiradores');
          break;

     default:
          $bot->sendMessage('No conozco ese comando');
          break;
}



?>
