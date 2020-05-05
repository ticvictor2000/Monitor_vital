<!DOCTYPE html>
<html lang="es" dir="ltr">
     <head>
          <meta charset="utf-8">
          <title>Principal</title>
     </head>
     <body>
          <?php require_once '../code/php/helpers.php'; ?>
          <?php if(!isset($_SESSION)) : session_start(); endif; ?>
          <?php if(isset($_SESSION['errors']['auth'])): ?>
               <p><?= $_SESSION['errors']['auth']; ?></p>
          <?php endif; ?>
          <p>Bienvenido <?= $_SESSION['user']['NAME']; ?></p>
          <a href="users.php">Gestionar usuarios</a>
          <a href="netdevices.php">Gestionar dispositivos de red</a>
          <a href="ports.php">Gestionar puertos</a>
          <button type="button" id="logoff">Cerrar sesión</button>
          <script src="../code/js/view_index.js"></script>
     </body>
</html>
