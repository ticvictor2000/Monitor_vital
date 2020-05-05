<!DOCTYPE html>
<html lang="en" dir="ltr">
     <head>
          <meta charset="utf-8">
          <title>Asignación de puertos</title>
     </head>
     <body>
          <?php require_once '../code/php/classes.php'; ?>
          <?php require_once '../code/php/helpers.php'; ?>
          <?php $db = new Db(); ?>
          <?php $pdo = $db->connect(); ?>
          <?php if (!isset($_SESSION)): ?>
               <?php session_start(); ?>
          <?php endif; ?>
          <?php auth('admin'); ?>
          <h1>Gestión de puertos</h1>
          <select id="type">
               <option value="null" selected disabled>--Selecciona tipo de dispositivo--</option>
               <option value="switch">Switch</option>
               <option value="ap">Punto de acceso (AP)</option>
          </select>
          <br />
          <select id="ndev">

          </select>
          <table id="ports" border="1">
               <tr>
                    <td>PUERTO</td>
                    <td>UBICACIÓN</td>
               </tr>
               <tr>
                    <td>FastEthernet 0/1</td>
                    <td>Habitación 302</td>
               </tr>
          </table>
          <strong id="error"></strong>
          <datalist id="locations">

          </datalist>
          <button id="update_locations">Actualizar</button>
          <script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
          <script src="../code/js/view_ports.js"></script>
     </body>
</html>
