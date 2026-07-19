<?php
require_once("../includes/session.php");
require_once("../config/conn.php");

if (!isset($_GET["id"])) {
    header("Location:index.php");
    exit();
}

$id = $_GET["id"];

// ดึงข้อมูลรูปก่อนลบ
$stmt = $conn->prepare("SELECT image FROM vehicles WHERE id=?");
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location:index.php");
    exit();
}

$row = $result->fetch_assoc();

// ลบไฟล์รูป (ถ้ามี)
if (!empty($row["image"])) {

    $path = "../uploads/vehicles/" . $row["image"];

    if (file_exists($path)) {
        unlink($path);
    }

}

// ลบข้อมูล
$stmt = $conn->prepare("DELETE FROM vehicles WHERE id=?");
$stmt->bind_param("s", $id);

if ($stmt->execute()) {

    header("Location:index.php?delete=1");

} else {

    echo $stmt->error;

}