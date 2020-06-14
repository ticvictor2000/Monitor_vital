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

$act = $_GET['act'];

if ($act == 'download_security') {
     $json_raw = getSecurity(true);
     $path = '../json/Security_'.date('Y_m_d_h_i_s').'.json';
     $filename = 'Security_'.date('Y_m_d_h_i_s').'.json';
     $json_file = fopen($path,'w');
     fwrite($json_file,$json_raw);
     fclose($json_file);

     header("Content-Type: application/octet-stream");
     header("Content-Transfer-Encoding: Binary");
     header("Content-disposition: attachment; filename=\"".$filename."\"");
     echo readfile($path);
}



?>
