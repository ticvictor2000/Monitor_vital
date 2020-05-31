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

$act = trim($_POST['act']);

if ($act == 'upd_userdata') {
     // Update only user data

     // Get data and validate //

     // Username
     $user = trim($_POST['user']);

     if (strlen($user)<1) {
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

     // Compare the data and try to change if necessary //

     $user_id = intval($_SESSION['user']['ID']);

     if ($user != $_SESSION['user']['USERNAME']) {
          $update_field = $db->updUserField($pdo,'USERNAME',$user,$user_id);
          if (!$update_field) {
               echo '*El usuario ' . $user . ' ya existe';
               die();
          }
          if ($update_field != true) {
               newLog($update_field->getMessage(), 'User data change (username)',3);
               echo '*Error interno, contacte con el administrador';
               die();
          }
          $_SESSION['user']['USERNAME'] = $user;
     }

     if ($email != $_SESSION['user']['EMAIL']) {
          $update_field = $db->updUserField($pdo,'EMAIL',$email,$user_id);
          if (!$update_field) {
               echo '*El correo electrónico ' . $email . ' ya existe';
               die();
          }
          if ($update_field != true) {
               newLog($update_field->getMessage(), 'User data change (email)',3);
               echo '*Error interno, contacte con el administrador';
               die();
          }
          $_SESSION['user']['EMAIL'] = $email;
     }

     if ($name != $_SESSION['user']['NAME']) {
          $update_field = $db->updUserField($pdo,'NAME',$name,$user_id,false);
          if (!$update_field) {
               echo '*El nombre ' . $name . ' ya existe';
               die();
          }
          if ($update_field != true) {
               newLog($update_field->getMessage(), 'User data change (name)',3);
               echo '*Error interno, contacte con el administrador';
               die();
          }
          $_SESSION['user']['NAME'] = $name;
     }

     if ($surname != $_SESSION['user']['SURNAME']) {
          $update_field = $db->updUserField($pdo,'SURNAME',$surname,$user_id,false);
          if (!$update_field) {
               echo '*El apellido ' . $surname . ' ya existe';
               die();
          }
          if ($update_field != true) {
               newLog($update_field->getMessage(), 'User data change (surname)',3);
               echo '*Error interno, contacte con el administrador';
               die();
          }
          $_SESSION['user']['SURNAME'] = $surname;
     }



     echo 'Datos del usuario actualizados correctamente';
}

if ($act == 'upd_userdata_all') {
     // Update all user data

     // Get data and validate //

     // Username
     $user = trim($_POST['user']);

     if (strlen($user)<1) {
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

     // Compare the data and try to change if necessary //

     $user_id = intval($_SESSION['user']['ID']);

     if ($user != $_SESSION['user']['USERNAME']) {
          $update_field = $db->updUserField($pdo,'USERNAME',$user,$user_id);
          if (!$update_field) {
               echo '*El usuario ' . $user . ' ya existe';
               die();
          }
          if ($update_field != true) {
               newLog($update_field->getMessage(), 'User data change (username)',3);
               echo '*Error interno, contacte con el administrador';
               die();
          }
          $_SESSION['user']['USERNAME'] = $user;
     }

     if ($email != $_SESSION['user']['EMAIL']) {
          $update_field = $db->updUserField($pdo,'EMAIL',$email,$user_id);
          if (!$update_field) {
               echo '*El correo electrónico ' . $email . ' ya existe';
               die();
          }
          if ($update_field != true) {
               newLog($update_field->getMessage(), 'User data change (email)',3);
               echo '*Error interno, contacte con el administrador';
               die();
          }
          $_SESSION['user']['EMAIL'] = $email;
     }

     if ($name != $_SESSION['user']['NAME']) {
          $update_field = $db->updUserField($pdo,'NAME',$name,$user_id,false);
          if ($update_field != true) {
               newLog($update_field->getMessage(), 'User data change (name)',3);
               echo '*Error interno, contacte con el administrador';
               die();
          }
          $_SESSION['user']['NAME'] = $name;
     }

     if ($surname != $_SESSION['user']['SURNAME']) {
          $update_field = $db->updUserField($pdo,'SURNAME',$surname,$user_id,false);
          if ($update_field != true) {
               newLog($update_field->getMessage(), 'User data change (surname)',3);
               echo '*Error interno, contacte con el administrador';
               die();
          }
          $_SESSION['user']['SURNAME'] = $surname;
     }

     // Update password

     $safe_npass = password_hash($npass, PASSWORD_BCRYPT, ['cost'=>4]);

     $update_field = $db->updUserField($pdo,'PASS',$safe_npass,$user_id,false);
     if ($update_field != true) {
          newLog($update_field->getMessage(), 'User data change (pass)',3);
          echo '*Error interno, contacte con el administrador';
          die();
     }
     $_SESSION['user']['PASS'] = $safe_npass;


     echo 'Datos del usuario actualizados correctamente';
}


?>
