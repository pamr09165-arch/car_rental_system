<?php
require_once("../includes/session.php");
require_once("../config/conn.php");

include("../includes/header.php");
include("../includes/navbar.php");

// ลูกค้า
$customer_sql = "SELECT id, full_name
                 FROM customers
                 ORDER BY full_name ASC";
$customers = mysqli_query($conn, $customer_sql);

// รถที่พร้อมให้เช่า
$vehicle_sql = "SELECT id,
                       brand,
                       model,
                       plate_no,
                       price_per_day
                FROM vehicles
                WHERE status='available'
                ORDER BY brand ASC";

$vehicles = mysqli_query($conn, $vehicle_sql);
?>

<div class="d-flex">

<?php include("../includes/sidebar.php"); ?>

<div class="container-fluid p-4">

<div class="d-flex justify-content-between align-items-center mb-3">

<h2>

<i class="bi bi-calendar-plus"></i>

เพิ่มการจองรถ

</h2>

<a href="index.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

กลับ

</a>

</div>

<hr>

<form
action="store.php"
method="POST">

<div class="row">

<!-- ลูกค้า -->

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

<?php while($customer=mysqli_fetch_assoc($customers)){ ?>

<option value="<?= $customer["id"] ?>">

<?= htmlspecialchars($customer["full_name"]) ?>

</option>

<?php } ?>

</select>

</div>

<!-- รถ -->

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

<?php while($vehicle=mysqli_fetch_assoc($vehicles)){ ?>

<option

value="<?= $vehicle["id"] ?>"

data-price="<?= $vehicle["price_per_day"] ?>">

<?= htmlspecialchars($vehicle["brand"]) ?>

<?= htmlspecialchars($vehicle["model"]) ?>

(

<?= htmlspecialchars($vehicle["plate_no"]) ?>

)

</option>

<?php } ?>

</select>

</div>

<!-- วันรับรถ -->

<div class="col-md-6 mb-3">

<label class="form-label">

วันรับรถ

</label>

<input

type="date"

name="start_date"

id="start_date"

class="form-control"

required>

</div>

<!-- วันคืนรถ -->

<div class="col-md-6 mb-3">

<label class="form-label">

วันคืนรถ

</label>

<input

type="date"

name="end_date"

id="end_date"

class="form-control"

required>

</div>

<!-- สถานที่รับรถ -->

<div class="col-md-6 mb-3">

<label class="form-label">

สถานที่รับรถ

</label>

<input

type="text"

name="pickup_location"

class="form-control">

</div>

<!-- สถานที่คืนรถ -->

<div class="col-md-6 mb-3">

<label class="form-label">

สถานที่คืนรถ

</label>

<input

type="text"

name="return_location"

class="form-control">

</div>

<!-- ราคา/วัน -->

<div class="col-md-4 mb-3">

<label class="form-label">

ราคา / วัน

</label>

<input

type="text"

id="price_per_day"

class="form-control"

readonly>

</div>

<!-- จำนวนวัน -->

<div class="col-md-4 mb-3">

<label class="form-label">

จำนวนวัน

</label>

<input

type="text"

id="days"

class="form-control"

readonly>

</div>

<!-- ค่าเช่ารวม -->

<div class="col-md-4 mb-3">

<label class="form-label">

ค่าเช่ารวม

</label>

<input

type="text"

id="total_price_show"

class="form-control"

readonly>

<input

type="hidden"

name="total_price"

id="total_price">

</div>

<!-- สถานะ -->

<div class="col-md-6 mb-3">

<label class="form-label">

สถานะ

</label>

<select

name="status"

class="form-select">

<option value="pending">

Pending

</option>

<option value="confirmed">

Confirmed

</option>

</select>

</div>
<!-- ปุ่ม -->

<div class="col-12">

<button
type="submit"
class="btn btn-success">

<i class="bi bi-save"></i>

บันทึกการจอง

</button>

<a
href="index.php"
class="btn btn-secondary">

ยกเลิก

</a>

</div>

</div>

</form>

</div>

</div>

<script>

const vehicle=document.getElementById("vehicle");

const start=document.getElementById("start_date");

const end=document.getElementById("end_date");

const price=document.getElementById("price_per_day");

const days=document.getElementById("days");

const total=document.getElementById("total_price");

const totalShow=document.getElementById("total_price_show");

vehicle.addEventListener("change",calculate);

start.addEventListener("change",calculate);

end.addEventListener("change",calculate);

function calculate(){

let selected=vehicle.options[vehicle.selectedIndex];

let pricePerDay=parseFloat(selected.dataset.price||0);

price.value=pricePerDay.toFixed(2);

if(start.value!="" && end.value!=""){

let d1=new Date(start.value);

let d2=new Date(end.value);

let diff=(d2-d1)/(1000*60*60*24)+1;

if(diff>0){

days.value=diff;

let sum=diff*pricePerDay;

total.value=sum;

totalShow.value=sum.toLocaleString("en-US",{

minimumFractionDigits:2,

maximumFractionDigits:2

});

}else{

days.value="";

total.value="";

totalShow.value="";

}

}

}

</script>

<?php include("../includes/footer.php"); ?>