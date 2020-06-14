<?php

// Requires

require_once 'classes.php';
require_once 'helpers.php';

// Connect to DB

$db = new Db();
$pdo = $db->connect();

// Get all the rows of clients

try {
     $clients = $pdo->query("SELECT `MACEQ`,TYPE FROM Medical_eq")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
     newLog($e,'Cleaner error getting clients',3);
     die();
}

// Check if the clients have a port assigned

foreach ($clients as $client) {
     $maceq = $client['MACEQ'];
     $type = $client['TYPE'];
     try {
          $asoc_clients = $pdo->query("SELECT `ID` FROM Ports WHERE MACEQ='$maceq'")->fetchAll(PDO::FETCH_ASSOC);
     } catch (PDOException $e) {
          newLog($e,'Cleaner error getting associated clients',3);
          die();
     }
     if (count($asoc_clients)==0) {
          // Remove client from the DB
          try {
               $remove_cli = $pdo->query("DELETE FROM Medical_eq WHERE MACEQ='$maceq'")->fetchAll(PDO::FETCH_ASSOC);
          } catch (PDOException $e) {
               newLog($e,'Cleaner error removing associated clients',3);
               die();
          }
     }
     if ($type == 'Network Device') {
          // Remove client from the DB
          try {
               $remove_ports_clientn = $pdo->query("UPDATE Ports SET MACEQ=null,IP_ADDR=null WHERE MACEQ='$maceq'");
               $remove_clientn = $pdo->query("DELETE FROM Medical_eq WHERE MACEQ='$maceq'");
          } catch (PDOException $e) {
               newLog($e,'Cleaner error removing the network clients',3);
               die();
          }
     }
}
















?>
