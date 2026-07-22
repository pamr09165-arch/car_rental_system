<?php
require_once("../includes/session.php");
require_once("../config/conn.php");

// ตรวจสอบว่ามี id ส่งมาหรือไม่
if (!isset($_GET["id"])) {
    header("Location:index.php");
    exit();
}

$id = $_GET["id"];

// ========================
// ตรวจสอบข้อมูลก่อนลบ
// ========================

$sql = "SELECT id
        FROM payments
        WHERE id=?
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location:index.php");
    exit();
}

// ========================
// ลบข้อมูล
// ========================

$delete = $conn->prepare("
DELETE FROM payments
WHERE id=?
");

$delete->bind_param("s", $id);

if ($delete->execute()) {

    header("Location:index.php?delete=1");
    exit();

} else {

    die("SQL Error : " . $delete->error);

}
?>