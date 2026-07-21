<?php
require_once("../includes/session.php");
require_once("../config/conn.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location:index.php");
    exit();
}

// ---------- Generate UUID ----------
function generateUUID()
{
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff)
    );
}

$id = generateUUID();

// ---------- รับค่าจากฟอร์ม ----------
$full_name = trim($_POST["full_name"]);
$phone = trim($_POST["phone"]);
$email = trim($_POST["email"]);
$id_card_no = trim($_POST["id_card_no"]);
$driver_license_no = trim($_POST["driver_license_no"]);
$address = trim($_POST["address"]);

// ---------- ตรวจสอบเลขบัตรประชาชนซ้ำ ----------
$check = $conn->prepare("SELECT id FROM customers WHERE id_card_no = ?");
$check->bind_param("s", $id_card_no);
$check->execute();

if ($check->get_result()->num_rows > 0) {

    echo "<script>
    alert('เลขบัตรประชาชนนี้มีอยู่ในระบบแล้ว');
    history.back();
    </script>";
    exit();
}

// ---------- ตรวจสอบใบขับขี่ซ้ำ ----------
if (!empty($driver_license_no)) {

    $check2 = $conn->prepare("SELECT id FROM customers WHERE driver_license_no = ?");
    $check2->bind_param("s", $driver_license_no);
    $check2->execute();

    if ($check2->get_result()->num_rows > 0) {

        echo "<script>
        alert('เลขใบขับขี่นี้มีอยู่ในระบบแล้ว');
        history.back();
        </script>";
        exit();
    }
}

// ---------- Upload รูปบัตร ----------
$idCardImage = NULL;

if (isset($_FILES["id_card_image"]) && $_FILES["id_card_image"]["error"] == 0) {

    $ext = strtolower(pathinfo($_FILES["id_card_image"]["name"], PATHINFO_EXTENSION));

    $idCardImage = uniqid("idcard_", true) . "." . $ext;

    move_uploaded_file(
        $_FILES["id_card_image"]["tmp_name"],
        "../uploads/customers/" . $idCardImage
    );
}

// ---------- Upload รูปใบขับขี่ ----------
$licenseImage = NULL;

if (isset($_FILES["license_image"]) && $_FILES["license_image"]["error"] == 0) {

    $ext = strtolower(pathinfo($_FILES["license_image"]["name"], PATHINFO_EXTENSION));

    $licenseImage = uniqid("license_", true) . "." . $ext;

    move_uploaded_file(
        $_FILES["license_image"]["tmp_name"],
        "../uploads/customers/" . $licenseImage
    );
}

// ---------- INSERT ----------
$sql = "INSERT INTO customers
(
id,
full_name,
phone,
email,
id_card_no,
driver_license_no,
address,
id_card_image,
license_image
)
VALUES
(
?,
?,
?,
?,
?,
?,
?,
?,
?
)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL Error : " . $conn->error);
}

$stmt->bind_param(
    "sssssssss",
    $id,
    $full_name,
    $phone,
    $email,
    $id_card_no,
    $driver_license_no,
    $address,
    $idCardImage,
    $licenseImage
);

if ($stmt->execute()) {

    header("Location:index.php?success=1");
    exit();

} else {

    echo "Error : " . $stmt->error;

}
?>