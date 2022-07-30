<?php
$servername = "localhost";
$user = "root";
$password = "@SequentialHeart198";
$database="IMG_ARS";

$connect = new mysqli($servername, $user, $password, $database);

if ($connect->connect_error) {
die("Connection failed: " . $connect->connect_error);
}
?>