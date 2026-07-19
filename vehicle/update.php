<?php
require_once("../includes/session.php");
require_once("../config/conn.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location:index.php");
    exit();
}

$id = $_POST["id"];

$brand = trim($_POST["brand"]);
$model = trim($_POST["model"]);
$year = !empty($_POST["year"]) ? $_POST["year"] : NULL;
$plate_no = trim($_POST["plate_no"]);
$price = $_POST["price_per_day"];
$deposit = !empty($_POST["deposit_amount"]) ? $_POST["deposit_amount"] : 0;
$location = trim($_POST["location"]);
$status = $_POST["status"];

/* ==========================
   ตรวจสอบทะเบียนรถซ้ำ
========================== */

$check = $conn->prepare("
SELECT id
FROM vehicles
WHERE plate_no = ?
AND id <> ?
");

$check->bind_param("ss", $plate_no, $id);
$check->execute();

if ($check->get_result()->num_rows > 0) {

    echo "<script>
        alert('ทะเบียนรถนี้ถูกใช้งานแล้ว');
        history.back();
    </script>";

    exit();
}

/* ==========================
   ดึงรูปเดิม
========================== */

$stmt = $conn->prepare("
SELECT image
FROM vehicles
WHERE id = ?
");

$stmt->bind_param("s", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location:index.php");
    exit();
}

$row = $result->fetch_assoc();

$imageName = $row["image"];

/* ==========================
   Upload รูปใหม่
========================== */

if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {

    $allowed = ["jpg", "jpeg", "png", "webp"];

    $extension = strtolower(
        pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION)
    );

    if (!in_array($extension, $allowed)) {
        die("อนุญาตเฉพาะ JPG, JPEG, PNG และ WEBP");
    }

    if ($_FILES["image"]["size"] > 5 * 1024 * 1024) {
        die("ไฟล์ต้องมีขนาดไม่เกิน 5 MB");
    }

    /* ตั้งชื่อไฟล์ใหม่ */

    $newImage = uniqid("car_", true) . "." . $extension;

    $uploadPath = "../uploads/vehicles/" . $newImage;

    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $uploadPath)) {
        die("Upload รูปไม่สำเร็จ");
    }

    /* ลบรูปเก่า */

    if (
        !empty($imageName) &&
        file_exists("../uploads/vehicles/" . $imageName)
    ) {
        unlink("../uploads/vehicles/" . $imageName);
    }

    $imageName = $newImage;
}

/* ==========================
   Update Database
========================== */

$sql = "
UPDATE vehicles
SET
brand=?,
model=?,
year=?,
plate_no=?,
price_per_day=?,
deposit_amount=?,
location=?,
image=?,
status=?,
updated_at=NOW()
WHERE id=?
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssisddssss",
    $brand,
    $model,
    $year,
    $plate_no,
    $price,
    $deposit,
    $location,
    $imageName,
    $status,
    $id
);

if ($stmt->execute()) {

    header("Location:index.php?update=1");
    exit();

} else {

    echo "เกิดข้อผิดพลาด : " . $stmt->error;

}