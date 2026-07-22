<?php
require_once("../includes/session.php");
require_once("../config/conn.php");

if (!isset($_GET["id"])) {
    header("Location:index.php");
    exit();
}

$id = $_GET["id"];

// ========================
// ดึงข้อมูล Booking
// ========================

$sql = "SELECT * FROM bookings WHERE id=? LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location:index.php");
    exit();
}

$row = $result->fetch_assoc();

// ========================
// ลูกค้า
// ========================

$customer = $conn->query("
SELECT
id,
full_name
FROM customers
ORDER BY full_name ASC
");

// ========================
// รถ
// ========================

$vehicle = $conn->query("
SELECT
id,
brand,
model,
plate_no,
price_per_day
FROM vehicles
ORDER BY brand ASC
");

include("../includes/header.php");
include("../includes/navbar.php");
?>

<div class="d-flex">

<?php include("../includes/sidebar.php"); ?>

<div class="container-fluid p-4">

<div class="card shadow">

<div class="card-header bg-warning text-dark">

<h4 class="mb-0">

<i class="bi bi-pencil-square"></i>

แก้ไขข้อมูลการจอง

</h4>

</div>

<div class="card-body">

<form
action="update.php"
method="POST">

<input
type="hidden"
name="id"
value="<?= $row["id"] ?>">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

ลูกค้า

</label>

<select
name="renter_id"
class="form-select"
required>

<option value="">

-- เลือกลูกค้า --

</option>

<?php while($c=$customer->fetch_assoc()){ ?>

<option
value="<?= $c["id"] ?>"

<?=($row["renter_id"]==$c["id"])?"selected":"";?>>

<?= htmlspecialchars($c["full_name"]) ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

รถ

</label>

<select
name="vehicle_id"
id="vehicle"
class="form-select"
required>

<option value="">

-- เลือกรถ --

</option>

<?php while($v=$vehicle->fetch_assoc()){ ?>

<option

value="<?= $v["id"] ?>"

data-price="<?= $v["price_per_day"] ?>"

<?=($row["vehicle_id"]==$v["id"])?"selected":"";?>>

<?= htmlspecialchars($v["brand"]) ?>

<?= htmlspecialchars($v["model"]) ?>

(<?= htmlspecialchars($v["plate_no"]) ?>)

</option>

<?php } ?>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

วันรับรถ

</label>

<input

type="date"

name="start_date"

id="start_date"

class="form-control"

value="<?= $row["start_date"] ?>"

required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

วันคืนรถ

</label>

<input

type="date"

name="end_date"

id="end_date"

class="form-control"

value="<?= $row["end_date"] ?>"

required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

สถานที่รับรถ

</label>

<input

type="text"

name="pickup_location"

class="form-control"

value="<?= htmlspecialchars($row["pickup_location"]) ?>"

required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

สถานที่คืนรถ

</label>

<input

type="text"

name="return_location"

class="form-control"

value="<?= htmlspecialchars($row["return_location"]) ?>"

required>

</div>
<div class="col-md-6 mb-3">

<label class="form-label">

สถานะ

</label>

<select
name="status"
class="form-select"
required>

<option
value="pending"
<?= ($row["status"]=="pending")?"selected":""; ?>>

Pending

</option>

<option
value="confirmed"
<?= ($row["status"]=="confirmed")?"selected":""; ?>>

Confirmed

</option>

<option
value="completed"
<?= ($row["status"]=="completed")?"selected":""; ?>>

Completed

</option>

<option
value="cancelled"
<?= ($row["status"]=="cancelled")?"selected":""; ?>>

Cancelled

</option>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

ค่าเช่า (บาท)

</label>

<input

type="number"

step="0.01"

name="total_price"

id="total_price"

class="form-control"

value="<?= $row["total_price"] ?>"

readonly>

</div>

</div>

<div class="text-end">

<a
href="index.php"
class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

กลับ

</a>

<button
type="submit"
class="btn btn-warning">

<i class="bi bi-save"></i>

บันทึกการแก้ไข

</button>

</div>

</form>

</div>

</div>

</div>

</div>

<script>

function calculatePrice(){

let start = $("#start_date").val();

let end = $("#end_date").val();

let price = $("#vehicle option:selected").data("price");

if(start!="" && end!="" && price){

let s = new Date(start);

let e = new Date(end);

let days = Math.ceil((e-s)/(1000*60*60*24))+1;

if(days<1){

days=1;

}

$("#total_price").val((days*price).toFixed(2));

}

}

$("#vehicle").change(calculatePrice);

$("#start_date").change(calculatePrice);

$("#end_date").change(calculatePrice);

$(document).ready(function(){

calculatePrice();

});

</script>

<?php include("../includes/footer.php"); ?>