<?php

// Requires

require_once 'classes.php';
require_once 'helpers.php';

// Connect to DB

$db = new Db();
$pdo = $db->connect();

// Get network devices

$ndevs = $db->getNetDevices($pdo);
if (!is_array($ndevs)) {
     newLog($ndevs,'Obtener dispositivos de red',4);
     die();
}

// For each network device we need to make a decision of the comm form

$clients_all = array();
$ica = 0;

foreach ($ndevs as $ndev) {
     // Check brand
     if ($ndev['BRAND'] == 'Cisco') {
          // Check supported protocol
          if (!$ndev['SSH']) {
               // Using telnet protocol
               $telnet_conn = new Telnet($ndev['IP_ADDR']);
               $cndt = new CiscoNetDeviceTelnet($telnet_conn,$ndev['PASS']);

               if (!$cndt->login()) {
                    newLog('Hubo un problema al conectarse al dispositivo ' . $ndev['MACND'],'Conectarse a dispositivos de red',4);
                    die();
               }

               $ports_macs = $cndt->getConnDevs($telnet_conn);
               $ports_macs_ex = explode("\n",$ports_macs);
               $clients = array();
               $ic = 0;
               for ($i=6; $i <  count($ports_macs_ex)-1; $i++) {

                    $client['NAME'] = explode('    ',$ports_macs_ex[$i])[3];
                    if (!in_array($client['NAME'],array_column($clients, 'NAME'))) {
                         $client['TYPE'] = null;
                         $client['MACEQ'] = macCiscoToStd(explode('    ',$ports_macs_ex[$i])[1]);
                         $client_ip_arr = explode("\n", $cndt->getIp($telnet_conn,$client['MACEQ']));

                         if (!isset($client_ip_arr[2])) {
                              $client['TYPE'] = 'Network Device';
                              $client['MACEQ'] =  $db->getBlankMac($pdo);
                              $client['IP_ADDR'] = $db->getBlankIp($pdo);
                         } else {
                              $client['TYPE'] = 'Not identified';
                              $client_ip = explode(' ',$client_ip_arr[2])[2];
                              $client['IP_ADDR'] = $client_ip;
                         }
                         $clients[$ic] = $client;
                         $ic++;
                    }
               }
          }
     }
     $clients_all[$ica]['MACND'] = $ndev['MACND'];
     $clients_all[$ica]['CLIENTS'] = $clients;
     $ica++;
}

// We now get all the clients on the db and compare

$clients_db = $db->getClients($pdo);
if (!is_array($clients_db)) {
     newLog($clients_db,'Error al obtener clientes de la db',4);
     die();
}

// Check which clients are contained in the database


$clients_to_create = array();
$clients_to_verify = array();
for ($i=0; $i < count($clients_all); $i++) {
     $client = $clients_all[$i]['CLIENTS'];
     for ($iic=0; $iic < count($client); $iic++) {
          $maceq = $client[$iic]['MACEQ'];
          if (searchMacDb($maceq,$clients_db) || $client[$iic]['TYPE']=='Network Device') {
               // That client is in the database
               $clients_to_verify[$iic]['MACND'] = $clients_all[$i]['MACND'];
               $clients_to_verify[$iic]['MACEQ'] = $maceq;
               $clients_to_verify[$iic]['NAME'] = $client[$iic]['NAME'];
               $clients_to_verify[$iic]['TYPE'] = $client[$iic]['TYPE'];
          } else {
               // That client is not in the database, we proceed to create it
               $clients_to_create[$iic]['MACEQ'] = $maceq;
               $clients_to_create[$iic]['IP_ADDR'] = $client[$iic]['IP_ADDR'];
               $clients_to_create[$iic]['NAME'] = $client[$iic]['NAME'];
               $clients_to_create[$iic]['TYPE'] = $client[$iic]['TYPE'];
          }
     }
}

// Create new client

if (count($clients_to_create)>0) {
     $clients_to_create_sql = '';
     foreach ($clients_to_create as $client_to_create) {
          $datetime = date('Y/m/d H:i:s');
          $maceq = $client_to_create['MACEQ'];
          $ipeq = $client_to_create['IP_ADDR'];
          $type = $client_to_create['TYPE'];
          $pname = trim($client_to_create['NAME']);
          $pname_prepared = substr($pname,0,2) . '%' . substr($pname,2,5);
          if ($type != 'Network Device') {
               $clients_to_create_sql .= "INSERT INTO Medical_eq VALUES ('$maceq','$type',null,null,'$datetime');\n";
          }
          $clients_to_create_sql .= "UPDATE Ports SET IP_ADDR='$ipeq',MACEQ='$maceq' WHERE NAME LIKE '$pname_prepared';\n";
     }

     $new_clients = $db->newClient($pdo,$clients_to_create_sql);
     if (is_string($new_clients)) {
          newLog($new_clients,'Crear nuevos clientes detectados en la DB',4);
          die();
     }
}

// Verify exixtsing client

if (count($clients_to_verify)>0) {
     $pname_db_sql = "SELECT NAME,MACEQ FROM Ports WHERE MACEQ=''";
     foreach ($clients_to_verify as $clients_to_verify) {
          $maceq = $clients_to_verify['MACEQ'];
          $pname_db_sql .= " OR MACEQ='$maceq' ";
     }

     var_dump($pname_db_sql);die();

     $pname_db = $db->getPname($pdo,$pname_db_sql);
     if (!is_array($pname_db)) {
          newLog($pname_db,'No se han podido obtener los datos de los puertos',4);
          die();
     }

}












?>
