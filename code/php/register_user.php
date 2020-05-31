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

// Get POST Data

// Get data and validate //

// Username
$user = trim($_POST['user']);

if (strlen($user)<1) {
     echo '*El usuario no puede estar vacío';
     die();
}

// Telegram username
$tuser = trim($_POST['tuser']);

if (strlen($tuser)<1) {
     echo '*El usuario no puede estar vacío';
     die();
}

// Email
$email = trim($_POST['email']);

if (strlen($email)<1) {
     echo '*El correo electrónico no puede estar vacío';
     die();
}

if (!filter_var('FILTER_VALIDATE_EMAIL')) {
     echo '*El correo electrónico es inválido';
     die();
}

// Role
$role = trim($_POST['role']);

if (strlen($role)<1) {
     echo '*El rol no puede estar vacío';
     die();
}

if ($role != 'admin' && $role != 'tech' && $role != 'medical') {
     newLog('El usuario '.$user.' ha intentado introducir un rol no válido al crear el usuario','Alerta de seguridad',3);
     echo 'Introduce un rol válido';
     die();
}

if ($role == 'admin') {
     if ($_SESSION['user']['role'] != 'admin') {
          newLog('El usuario '.$user.' ha intentado crear un usuario administrador','Alerta de seguridad',3);
          echo 'Introduce un rol válido';
          die();
     }
}

// Name
$name = trim($_POST['name']);

if (strlen($name)<1) {
     echo '*El nombre no puede estar vacío';
     die();
}

// Surname
$surname = trim($_POST['surname']);

if (strlen($surname)<1) {
     echo '*El apellido no puede estar vacío';
     die();
}

// Password
$pass = trim($_POST['password']);
$passc = trim($_POST['passwordc']);

if (strlen($pass)<1 || strlen($passc)<1) {
     echo '*La contraseña no puede estar vacía';
     die();
}

if ($pass != $passc) {
     echo 'Las contraseñas no coinciden';
     die();
}

// Encrypt password //

$pass_safe = password_hash($pass, PASSWORD_BCRYPT, ['cost'=>4]);

// Create user in the database //

$data = array(
     'name' => $name,
     'surname' => $surname,
     'user' => $user,
     'email' => $email,
     'tuser' => $tuser,
     'pass' => $pass_safe,
     'role' => $role
);

$reg = $db->regUser($pdo,$data);
if ($reg) {
     echo 'Usuario creado correctamente';
     die();
} else {
     newLog($reg,'Crear usuario',3);
     echo 'Error interno al crear el usuario';
     die();
}








?>
