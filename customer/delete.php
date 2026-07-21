<?php
require_once("../includes/session.php");
require_once("../config/conn.php");

if (!isset($_GET["id"])) {
    header("Location:index.php");
    exit();
}

$id = $_GET["id"];

// ดึงข้อมูลรูปก่อนลบ
$stmt = $conn->prepare("SELECT id_card_image, license_image FROM customers WHERE id=?");
$stmt->bind_param("s", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location:index.php");
    exit();
}

$row = $result->fetch_assoc();

// ลบรูปบัตรประชาชน
if (
    !empty($row["id_card_image"]) &&
    file_exists("../uploads/customers/" . $row["id_card_image"])
) {
    unlink("../uploads/customers/" . $row["id_card_image"]);
}

// ลบรูปใบขับขี่
if (
    !empty($row["license_image"]) &&
    file_exists("../uploads/customers/" . $row["license_image"])
) {
    unlink("../uploads/customers/" . $row["license_image"]);
}

// ลบข้อมูล
$stmt = $conn->prepare("DELETE FROM customers WHERE id=?");
$stmt->bind_param("s", $id);

if ($stmt->execute()) {

    header("Location:index.php?delete=1");
    exit();

} else {

    echo "Error : " . $stmt->error;

}
?>