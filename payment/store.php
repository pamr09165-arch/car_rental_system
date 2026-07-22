<?php
require_once("../includes/session.php");
require_once("../config/conn.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location:index.php");
    exit();
}

// ========================
// รับค่าจากฟอร์ม
// ========================

$booking_id = $_POST["booking_id"];
$amount     = $_POST["amount"];
$method     = trim($_POST["method"]);
$status     = $_POST["status"];
$paid_at    = !empty($_POST["paid_at"]) ? $_POST["paid_at"] : NULL;

// ========================
// สร้าง UUID
// ========================

$id = bin2hex(random_bytes(16));

// แปลงเป็น UUID Format
$id = substr($id,0,8)."-".
      substr($id,8,4)."-".
      substr($id,12,4)."-".
      substr($id,16,4)."-".
      substr($id,20,12);

// ========================
// ตรวจสอบ Booking ซ้ำ
// ========================

$check = $conn->prepare("
SELECT id
FROM payments
WHERE booking_id=?
LIMIT 1
");

$check->bind_param("s",$booking_id);
$check->execute();

if($check->get_result()->num_rows > 0){

    echo "<script>

    alert('Booking นี้มีการชำระเงินแล้ว');

    window.location='create.php';

    </script>";

    exit();

}

// ========================
// เพิ่มข้อมูล
// ========================

$sql = "

INSERT INTO payments
(
id,
booking_id,
amount,
method,
status,
paid_at
)

VALUES
(
?,
?,
?,
?,
?,
?
)

";

$stmt = $conn->prepare($sql);


$stmt->bind_param(

"ssdsss",

$id,
$booking_id,
$amount,
$method,
$status,
$paid_at

);

if($stmt->execute()){

    header("Location:index.php?success=1");
    exit();

}else{

    die("SQL Error : ".$stmt->error);

}