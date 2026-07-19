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

// Admin ที่ Login อยู่ (ภายหลังจะเปลี่ยนเป็น Owner จริง)
$owner_id = $_SESSION["user_id"];

// ---------- รับค่าจากฟอร์ม ----------
$brand = trim($_POST["brand"]);
$model = trim($_POST["model"]);
$year = !empty($_POST["year"]) ? $_POST["year"] : NULL;
$plate_no = trim($_POST["plate_no"]);
$price_per_day = $_POST["price_per_day"];
$deposit_amount = !empty($_POST["deposit_amount"]) ? $_POST["deposit_amount"] : 0;
$location = trim($_POST["location"]);
$status = $_POST["status"];

// ---------- Upload Image ----------
$imageName = NULL;

if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {

    $allowed = ["jpg", "jpeg", "png", "webp"];

    $extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));

    if (!in_array($extension, $allowed)) {
        die("อนุญาตเฉพาะไฟล์ JPG, PNG และ WEBP");
    }

    if ($_FILES["image"]["size"] > 5 * 1024 * 1024) {
        die("ไฟล์ต้องมีขนาดไม่เกิน 5 MB");
    }

    $imageName = uniqid("car_", true) . "." . $extension;

    $uploadPath = "../uploads/vehicles/" . $imageName;

    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $uploadPath)) {
        die("อัปโหลดรูปไม่สำเร็จ");
    }
}

// ---------- INSERT ----------
$sql = "INSERT INTO vehicles
(
id,
owner_id,
brand,
model,
year,
plate_no,
price_per_day,
deposit_amount,
location,
image,
status
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
?,
?,
?
)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssssisdssss",
    $id,
    $owner_id,
    $brand,
    $model,
    $year,
    $plate_no,
    $price_per_day,
    $deposit_amount,
    $location,
    $imageName,
    $status
);

if ($stmt->execute()) {

    header("Location:index.php?success=1");
    exit();

} else {

    echo "Error : " . $stmt->error;

}