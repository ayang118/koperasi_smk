<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "db_koperasismk"; // Pastikan nama database sesuai

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>