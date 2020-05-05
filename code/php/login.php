<?php

// Including classes and functions

require_once 'classes.php';
require_once 'helpers.php';

// Starting session
if (!isset($_SESSION)) {
     session_start();
}

// Connecting to the database

$db = new Db();
$pdo = $db->connect();
if (is_string($pdo)) {
     loginError('Error interno', $pdo, 4);
}

// Get all parameters from the login form and trimming

$username = trim($_POST['user']);
$pass = trim($_POST['pass']);

// Load the config file in order to obtain auth type

$cnf = fopen('../json/cnf.json','r');
if (!$cnf) {
     loginError('Error interno','Error al abrir el fichero de configuración desde login.php',4);
     die();
}

$cnf_arr = json_decode(file_get_contents('../json/cnf.json'), true);
if (!isset($cnf_arr['General'])) {
     loginError('Error interno', 'El fichero de configuración está corrupto o no se ha podido leer. No se encuentra el apartado general',4);
     die();
}

if ($cnf_arr['General']['auth_type'] == 'DB') {
     // Database user authentication

     // Check if user exists
     try {
          $nrows_user = $pdo->query("SELECT USERNAME FROM Users WHERE USERNAME='$username'")->rowCount();
     } catch (PDOException $e) {
          loginError('Error interno',$e->getMessage(),4);
          die();
     }
     if ($nrows_user < 1) {
          loginError('El usuario o la contraseña son incorrectos','El usuario ' . $username . ' no existe en la BD',2);
          die();
     }

     // Obtain all the data from the user
     try {
          $user_data = $pdo->query("SELECT * FROM Users WHERE USERNAME='$username'")->fetchAll(PDO::FETCH_ASSOC);
     } catch (PDOException $e) {
          loginError('Error interno',$e->getMessage(),4);
          die();
     }

     // Verify user password

     if (password_verify($pass, $user_data[0]['PASS'])) {
          $_SESSION['user'] = $user_data[0];
          unset($_SESSION['errors']);
          header('Location: ../../view');
          die();
     } else {
          loginError('El usuario o la contraseña son incorrectos', 'Password incorrect for user ' . $username,2);
          die();
     }
}

if ($cnf_arr['General']['auth_type'] == 'LDAP') {
     // ADDS AUTHENTICATION

     $adcon = ldap_connect('ldap.forumsys.com') or die('Hubo un problema al conectarse al servidor LDAP');

     $aduser = 'uid=newton';
     $adpass = 'password';

     if (@ldap_bind($adcon,$aduser,$adpass)) {
          echo 'Autenticado';
     } else {
          echo 'Credenciales no válidas';
     }
}






?>
