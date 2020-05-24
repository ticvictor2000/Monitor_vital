<!DOCTYPE html>
<html lang="es" dir="ltr">
     <head>
          <meta charset="utf-8">
          <title>Gestión de usuarios</title>
     </head>
     <body>
          <?php require_once '../code/php/classes.php'; ?>
          <?php require_once '../code/php/helpers.php'; ?>
          <?php $db = new Db(); ?>
          <?php $pdo = $db->connect(); ?>
          <?php if (!isset($_SESSION)): ?>
               <?php session_start(); ?>
          <?php endif; ?>
          <?php auth('tech'); ?>
          <table border="1">
                 <tr>
                        <td>NOMBRE</td>
                        <td>USUARIO</td>
                        <td>CONTRASEÑA</td>
                        <td>ROL</td>
                 </tr>
                 <?php for($i=0; $i < count($db->getUsers($pdo)); $i++): ?>
                      <tr>
                             <td><?= $db->getUsers($pdo)[$i]['NAME'] ?></td>
                             <td><?= $db->getUsers($pdo)[$i]['USERNAME'] ?></td>
                             <td><?= $db->getUsers($pdo)[$i]['PASS'] ?></td>
                             <td><?= $db->getUsers($pdo)[$i]['ROLE'] ?></td>
                      </tr>
                 <?php endfor; ?>
          </table>
          <form action="../code/php/register_user.php" method="post">
               <input type="text" placeholder="Nombre" id="name_field" />
               <input type="text" placeholder="Usuario" id="user_field" />
               <input type="password" placeholder="Contraseña" id="pass_field" />
               <select id="role_field">
                    <option value="medical">Médico</option>
                    <?php if ($_SESSION['user']['ROLE'] == 'admin' || $_SESSION['user']['ROLE'] == 'tech'): ?>
                         <option value="tech">Técnico</option>
                    <?php endif; ?>
                    <?php if ($_SESSION['user']['ROLE'] == 'admin'): ?>
                         <option value="admin">Administrador</option>
                    <?php endif; ?>
               </select>
               <button type="button" id="register">Registrar</button>
          </form>
          <p><span id="result"></span></p>
          <script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
          <script src="../code/js/view_users.js"></script>
     </body>
</html>
