<?php
session_start();
include 'connect.php';

// clear session data
$seller_id = $_SESSION['seller_id'] ?? '';

// destroy session
session_destroy();

// redirect
header('Location:../admin/login.php');
exit;
?>