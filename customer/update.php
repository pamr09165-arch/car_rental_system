<?php
require_once("../includes/session.php");
require_once("../config/conn.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location:index.php");
    exit();
}

$id = $_POST["id"];
$full_name = trim($_POST["full_name"]);
$phone = trim($_POST["phone"]);
$email = trim($_POST["email"]);
$id_card_no = trim($_POST["id_card_no"]);
$driver_license_no = trim($_POST["driver_license_no"]);
$address = trim($_POST["address"]);

// =====================
// ตรวจสอบเลขบัตรประชาชนซ้ำ
// =====================
$check = $conn->prepare("SELECT id FROM customers WHERE id_card_no=? AND id<>?");
$check->bind_param("ss", $id_card_no, $id);
$check->execute();

if ($check->get_result()->num_rows > 0) {

    echo "<script>
    alert('เลขบัตรประชาชนนี้ถูกใช้งานแล้ว');
    history.back();
    </script>";

    exit();
}

// =====================
// ตรวจสอบเลขใบขับขี่ซ้ำ
// =====================
if (!empty($driver_license_no)) {

    $check = $conn->prepare("SELECT id FROM customers WHERE driver_license_no=? AND id<>?");
    $check->bind_param("ss", $driver_license_no, $id);
    $check->execute();

    if ($check->get_result()->num_rows > 0) {

        echo "<script>
        alert('เลขใบขับขี่นี้ถูกใช้งานแล้ว');
        history.back();
        </script>";

        exit();
    }
}

// =====================
// ดึงข้อมูลเดิม
// =====================
$stmt = $conn->prepare("SELECT * FROM customers WHERE id=?");
$stmt->bind_param("s", $id);
$stmt->execute();

$old = $stmt->get_result()->fetch_assoc();

$idCardImage = $old["id_card_image"];
$licenseImage = $old["license_image"];

// =====================
// Upload รูปบัตรใหม่
// =====================
if (isset($_FILES["id_card_image"]) && $_FILES["id_card_image"]["error"] == 0) {

    if (!empty($idCardImage) && file_exists("../uploads/customers/" . $idCardImage)) {
        unlink("../uploads/customers/" . $idCardImage);
    }

    $ext = strtolower(pathinfo($_FILES["id_card_image"]["name"], PATHINFO_EXTENSION));

    $idCardImage = uniqid("idcard_", true) . "." . $ext;

    move_uploaded_file(
        $_FILES["id_card_image"]["tmp_name"],
        "../uploads/customers/" . $idCardImage
    );
}

// =====================
// Upload รูปใบขับขี่ใหม่
// =====================
if (isset($_FILES["license_image"]) && $_FILES["license_image"]["error"] == 0) {

    if (!empty($licenseImage) && file_exists("../uploads/customers/" . $licenseImage)) {
        unlink("../uploads/customers/" . $licenseImage);
    }

    $ext = strtolower(pathinfo($_FILES["license_image"]["name"], PATHINFO_EXTENSION));

    $licenseImage = uniqid("license_", true) . "." . $ext;

    move_uploaded_file(
        $_FILES["license_image"]["tmp_name"],
        "../uploads/customers/" . $licenseImage
    );
}

// =====================
// UPDATE
// =====================
$sql = "UPDATE customers SET
full_name=?,
phone=?,
email=?,
id_card_no=?,
driver_license_no=?,
address=?,
id_card_image=?,
license_image=?
WHERE id=?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "sssssssss",
    $full_name,
    $phone,
    $email,
    $id_card_no,
    $driver_license_no,
    $address,
    $idCardImage,
    $licenseImage,
    $id
);

if ($stmt->execute()) {

    header("Location:index.php?update=1");
    exit();

} else {

    echo $stmt->error;

}
?>