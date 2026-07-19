<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "car_rental_system";   // เปลี่ยนเป็นชื่อฐานข้อมูลของคุณ

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection Failed : " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");