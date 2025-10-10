<?php
$servername = "localhost";
$username   = "contactuser";   // full cPanel username with prefix
$password   = "Embpython@2020";               // your user password
$dbname     = "contactdb";     // full database name with prefix

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
