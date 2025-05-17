<?php
$servername = "localhost";
$dbuser = "root";
$dbpassword = "";
$dbname = "usuarios_db";

$conn = new mysqli($servername, $dbuser, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
$conn->set_charset("utf8");
?>

