<?php
require_once("../includes/session.php");
require_once("../config/conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$id=$_POST["id"];
$brand=trim($_POST["brand"]);
$model=trim($_POST["model"]);
$year=$_POST["year"];
$plate_no=trim($_POST["plate_no"]);
$price=$_POST["price_per_day"];
$deposit=$_POST["deposit_amount"];
$location=trim($_POST["location"]);
$status=$_POST["status"];

// ตรวจสอบทะเบียนรถซ้ำ (ยกเว้นรถคันปัจจุบัน)
$check = $conn->prepare("SELECT id FROM vehicles WHERE plate_no = ? AND id <> ?");
$check->bind_param("ss", $plate_no, $id);
$check->execute();

if ($check->get_result()->num_rows > 0) {

    echo "<script>
    alert('ทะเบียนรถนี้ถูกใช้งานแล้ว');
    history.back();
    </script>";

    exit();

}

$sql="UPDATE vehicles
SET
brand=?,
model=?,
year=?,
plate_no=?,
price_per_day=?,
deposit_amount=?,
location=?,
status=?
WHERE id=?";

$stmt=$conn->prepare($sql);

$stmt->bind_param(

"ssisddsss",

$brand,
$model,
$year,
$plate_no,
$price,
$deposit,
$location,
$status,
$id

);

if($stmt->execute()){

header("Location:index.php?update=1");

exit();

}else{

echo $stmt->error;

}

}