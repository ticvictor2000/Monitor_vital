<?php

// Starting session

if (!isset($_SESSION)) {
     session_start();
}

// Destroy session and redirect to login

session_destroy();
header('Location: ../../');

?>
