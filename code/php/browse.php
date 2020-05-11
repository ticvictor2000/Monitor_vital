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
               $clients_to_verify[$iic]['IP_ADDR'] = $client[$iic]['IP_ADDR'];
          } else {
               // That client is not in the database, we proceed to create it
               $clients_to_create[$iic]['MACEQ'] = $maceq;
               $clients_to_create[$iic]['IP_ADDR'] = $client[$iic]['IP_ADDR'];
               $clients_to_create[$iic]['NAME'] = $client[$iic]['NAME'];
               $clients_to_create[$iic]['TYPE'] = $client[$iic]['TYPE'];
          }
     }
}

// Verify exixtsing client

if (count($clients_to_verify)>0) {
     foreach ($clients_to_verify as $client_to_verify) {
          if ($client_to_verify['TYPE'] == 'Network Device') {
               // We check that in the port of that Network Device has a NetDevice(Client)
               $is_client_net = $db->ClientIsNd($pdo,$client_to_verify['MACND'],$client_to_verify['NAME']);
               if (!$is_client_net) {
                    // That is not a network device, we need to update that port
                    $updport = $db->UpdNCliPort($pdo,$client_to_verify['MACND'],$client_to_verify['NAME'],$client_to_verify['MACEQ'],$client_to_verify['IP_ADDR']);

                    if (is_string($updport)) {
                         newLog($updport, 'Updating Port Data',4);
                         die();
                    } else {
                         // Port updated successful, now we update the last seen time of network devices
                         $db->updLastSeen($pdo);
                    }
               } else {
                    if (is_string($is_client_net)) {
                         newLog($is_client_net,'Verificar que el cliente no se ha movido',4);
                         die();
                    }
                    // In that ND Port is a Network Client
                    // We will update the last seen time
                    $db->updLastSeen($pdo);
               }
          } else {
               // In the port there is a client, we check if client's MAC match with DB MAC for that port
               $match_port_mac = $db->checkPortMac($pdo,$client_to_verify['NAME'],$client_to_verify['MACEQ'],$client_to_verify['MACND']);
               if (!$match_port_mac) {
                    // The client wasn't on that port
                    $upd_cli_port = $db->UpdCliPort($pdo,$client_to_verify['MACEQ'],$client_to_verify['MACND'],$client_to_verify['IP_ADDR'],$client_to_verify['NAME']);
                    if (is_string($upd_cli_port)) {
                         newLog($upd_cli_port, 'Updating Port Data',4);
                         die();
                    } else {
                         // Port updated successful
                         // We now to update the last seen time
                         $db->updLastSeen($pdo,$client_to_verify['MACEQ']);
                    }
               } else {
                    if (is_string($match_port_mac)) {
                         newLog($is_client_net,'Verificar que el cliente no se ha movido',4);
                         die();
                    }
                    // The client was on that port, we update the last seen time
                    $db->updLastSeen($pdo,$client_to_verify['MACEQ']);
               }

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






?>
