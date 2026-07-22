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

$id       = $_POST["id"];
$amount   = $_POST["amount"];
$method   = trim($_POST["method"]);
$status   = $_POST["status"];
$paid_at  = !empty($_POST["paid_at"]) ? $_POST["paid_at"] : NULL;

// ========================
// Update
// ========================

$sql = "

UPDATE payments

SET

amount=?,
method=?,
status=?,
paid_at=?

WHERE id=?

";

$stmt = $conn->prepare($sql);

$stmt->bind_param(

"dssss",

$amount,
$method,
$status,
$paid_at,
$id

);

if($stmt->execute()){

    header("Location:index.php?update=1");
    exit();

}else{

    die("SQL Error : ".$stmt->error);

}
?>