<?php

require_once 'classes.php';
require_once 'helpers.php';
$db = new Db();
$pdo = $db->connect();

if (!isset($_SESSION)) {
     session_start();
}

$name = trim($_POST['name']);
$user = trim($_POST['user']);
$pass = trim($_POST['pass']);
$role = trim($_POST['role']);

$role_session = $_SESSION['user']['ROLE'];

if ($role_session == 'tech' && $role == 'admin') {
     echo 'Hubo un error interno';
     newLog('El usuario ' . $_SESSION['user']['USERNAME'] . ' ha intentado crear un usuario con privilegios superiores a los suyos','Alerta de Seguridad',3);
     die();
}

// Encrypt password

$pass_safe = password_hash($pass, PASSWORD_BCRYPT, ['cost'=>4]);

try {
     $new_user = $pdo->query("INSERT INTO Users VALUES (null,'$name','$user','$pass_safe','$role')");
} catch (PDOException $e) {
     echo $e->getMessage();
     die();
}
echo "Success";







?>
