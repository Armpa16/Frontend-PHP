<?php

// เชื่อมต่อฐานข้อมูล
$servername = "yamabiko.proxy.rlwy.net";
$port=13821;
$username = "root";
$password = "EKRiEnzCXzuGRzsXOanDuXnpFPvzKpOv";
$dbname = "railway";

$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>