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
/*
if (!$bot->isGroup() && !$bot->isAdmin()) {
     $bot->sendMessage('No puedo hablar por aquí contigo');
}
*/
switch ($msg) {
     case '/test@MonitorVitalBot':
          $bot->sendMessage('TEST@TEST.COM');
          break;

     default:
          $bot->sendMessage('No conozco ese comando');
          break;
}



?>
