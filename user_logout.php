<?php
session_start();
include 'connect.php';

// clear session data
$seller_id = $_SESSION['user_id'] ?? '';

// destroy session
session_destroy();

// redirect
header('Location:../login.php');
exit;
?>