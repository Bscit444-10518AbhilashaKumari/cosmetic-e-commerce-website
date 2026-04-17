<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "makeupweb_db";
$port       = 3307;

try {
    $conn = new PDO(
        "mysql:host=$servername;dbname=$database;port=$port;charset=utf8mb4",
        $username,
        $password
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

/* UNIQUE ID FUNCTION */
if (!function_exists('unique_id')) {
    function unique_id() {
        return uniqid('', true);
    }
}
