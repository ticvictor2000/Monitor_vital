<!DOCTYPE html>
<html lang="es" dir="ltr">
     <head>
          <meta charset="utf-8">
          <title>Dispositivos de red</title>
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

          <h1>Dispositivos de red</h1>
          <table border="1">
               <tr>
                    <td>Dirección MAC</td>
                    <td>Tipo</td>
                    <td>Dirección IP</td>
                    <td>SSH</td>
                    <td>TELNET</td>
                    <td>Número de puertos</td>
                    <td>Marca</td>
                    <td>Modelo</td>
               </tr>
               <?php for($i=0; $i < count($db->getNetDevices($pdo)); $i++): ?>
                    <tr>
                         <td><?= $db->getNetDevices($pdo)[$i]['MACND'] ?></td>
                         <td><?= $db->getNetDevices($pdo)[$i]['TYPE'] ?></td>
                         <td><?= $db->getNetDevices($pdo)[$i]['IP_ADDR'] ?></td>
                         <?php if ($db->getNetDevices($pdo)[$i]['SSH']): ?>
                              <td>SÍ</td>
                         <?php endif; ?>
                         <?php if (!$db->getNetDevices($pdo)[$i]['SSH']): ?>
                              <td>NO</td>
                         <?php endif; ?>
                         <?php if ($db->getNetDevices($pdo)[$i]['TELNET']): ?>
                              <td>SÍ</td>
                         <?php endif; ?>
                         <?php if (!$db->getNetDevices($pdo)[$i]['TELNET']): ?>
                              <td>NO</td>
                         <?php endif; ?>
                         <td><?= $db->getNetDevices($pdo)[$i]['NPORTS'] ?></td>
                         <td><?= $db->getNetDevices($pdo)[$i]['BRAND'] ?></td>
                         <td><?= $db->getNetDevices($pdo)[$i]['MODEL'] ?></td>
                    </tr>
               <?php endfor; ?>
          </table>

          <h2>Añadir dispositivo de red</h2>
          <form id="nnd_form" action="#">
               <select id="type">
                    <option disabled selected value="null">--Tipo de dispositivo de red--</option>
                    <option value="switch">Switch</option>
                    <option value="ap">AP (Punto de acceso inalámbrico)</option>
               </select>
               <input type="text" placeholder="Dirección IP" id="ip" />
               <select id="telnet">
                    <option disabled selected value="null">--Soporte Telnet--</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
               </select>
               <select id="ssh">
                    <option disabled selected value="null">--Soporte SSH--</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
               </select>
               <select id="brand">
                    <option disabled selected value="null">--Marca del dispositivo--</option>
                    <option value="cisco">Cisco</option>
                    <option value="openwrt">Otras (OpenWrt Compatible)</option>
               </select>
               <input type="text" placeholder="Modelo" id="model" />
               <input type="text" placeholder="Contraseña Telnet/SSH" id="pass" />
               <input type="submit" value="Registrar" />
          </form>
          <p><span id="result"></span></p>
          <script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
          <script src="../code/js/view_netdevices.js"></script>
     </body>
</html>
