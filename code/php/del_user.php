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

// Retrieve post data

$dusername = trim($_POST['dusername']);
$dusernamec = trim($_POST['dusernamec']);

// Verify data and permissions

if ($dusername == '' || $dusernamec == '') {
     echo '*Introduzca el nombre de usuario a eliminar';
     die();
}

if ($dusername != $dusernamec) {
     echo '*Los nombres de usuario no coinciden';
     die();
}

if ($_SESSION['user']['ROLE'] != 'admin') {
     if ($db->getRole($pdo,$dusername) == 'admin') {
          echo '*Error interno. Contacte al administrador';
          newError('El usuario '.$_SESSION['user']['USERNAE'].' ha intentado borrar el administrador '.$dusername,'Alerta de seguridad',3);
          die();
     }
}

// Execute action (delete user)

if ($db->delUser($pdo,$dusername) != true) {
     echo '*Error interno. Contacte al administrador';
     newError('Usuario: '.$dusername,'Eliminar usuario',4);
     die();
}

echo 'Usuario eliminado correctamente';









?>
