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

// Get data and validate //

// Username
$user = trim($_POST['user']);

if (strlen($user)<1) {
     echo '*El usuario no puede estar vacío';
     die();
}

// Password
$npass = trim($_POST['npassword']);
$npassc = trim($_POST['npasswordc']);

if (strlen($npass)<1 || strlen($npassc)<1) {
     echo '*La contraseña no puede estar vacía';
     die();
}

if ($npass != $npassc) {
     echo 'Las contraseñas no coinciden';
     die();
}

// Update the password //

if ($db->getRole($pdo,$user) == 'admin' && $_SESSION['user']['ROLE'] != 'admin') {
     echo 'El usuario no existe';
     newLog('El usuario '.$user.' ha intentado cambiar la contraseña a un administrador','Alerta de seguridad',3);
     die();
}

$npass_safe = password_hash($npass, PASSWORD_BCRYPT, ['cost'=>4]);

$upd_pass = $db->updPass($pdo,$user,$npass_safe);

if ($upd_pass) {
     $_SESSION['user']['PASS'] = $npass_safe;
     echo 'Contraseña actualizada correctamente';
     die();
} else {
     newLog($upd_pass,'Cambio de contraseña del usuario '.$user,3);
     echo 'Error al cambiar la contraseña, por favor contacte con el administrador';
}




?>
