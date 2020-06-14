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

// Get mac of the network device

$macnd = trim($_POST['macnd']);

if ($macnd == '') {
     echo '*Introduce una dirección MAC del dispositivo de red a eliminar';
     die();
}

if (!filter_var('FILTER_VALIDATE_MAC')) {
     echo '*Dirección MAC inválida';
     die();
}

$removend = $db->removeNd($pdo,$macnd);

if ($removend) {
     echo 'Dispositivo borrado con éxito';
} else {
     echo '*Ha habido un error interno al borrar el dispositivo de red';
     newLog($removend,'Borrado de dispositivos de red',4);
     die();
}



?>
