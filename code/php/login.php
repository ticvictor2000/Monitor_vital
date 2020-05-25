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
     newLog('Error en el login principal', $pdo, 4);
     echo 'Error general, contacte con el Administrador';
     die();
}

// Get all parameters from the login form and trimming

$username = trim($_POST['username']);
$pass = trim($_POST['pass']);

if (strlen($username)<1) {
     newLog('Error en el login principal', 'No se ha introducido el nombre de usuario', 1);
     echo 'Introduce un nombre de usuario';
     die();
}

if (strlen($pass)<1) {
     newLog('Error en el login principal', 'No se ha introducido la contraseña', 1);
     echo 'Introduce una contraseña';
     die();
}

// Load the config file in order to obtain auth type

$cnf = fopen('../json/cnf.json','r');
if (!$cnf) {
     newLog('Error en el login principal', 'Error al abrir el fichero de configuración desde login.php', 4);
     echo 'Error general, contacte con el Administrador';
     die();
}
fclose($cnf);

$cnf_arr = json_decode(file_get_contents('../json/cnf.json'), true);
if (!isset($cnf_arr['General'])) {
     newLog('Error en el login principal', 'El fichero de configuración está corrupto o no se ha podido leer. No se encuentra el apartado general', 4);
     echo 'Error general, contacte con el Administrador';
     die();
}

if ($cnf_arr['General']['auth_type'] == 'DB') {
     // Database user authentication

     // Check if user exists
     try {
          $nrows_user = $pdo->query("SELECT USERNAME FROM Users WHERE USERNAME='$username'")->rowCount();
     } catch (PDOException $e) {
          newLog('Error en el login principal', $e->getMessage(), 4);
          echo 'Error general, contacte con el Administrador';
          die();
     }
     if ($nrows_user < 1) {
          newLog('Error en el login principal', 'El usuario ' . $username . ' no existe en la BD', 2);
          echo 'El usuario o la contraseña son incorrectos';
          die();
     }

     // Obtain all the data from the user
     try {
          $user_data = $pdo->query("SELECT * FROM Users WHERE USERNAME='$username'")->fetchAll(PDO::FETCH_ASSOC);
     } catch (PDOException $e) {
          newLog('Error en el login principal', $e->getMessage(), 2);
          echo 'Error interno';
          die();
     }

     // Verify user password

     if (password_verify($pass, $user_data[0]['PASS'])) {
          $_SESSION['user'] = $user_data[0];
          echo true;
          die();
     } else {
          newLog('Error en el login principal', 'Password incorrect for user ' . $username, 2);
          echo 'El usuario o la contraseña son incorrectos';
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
