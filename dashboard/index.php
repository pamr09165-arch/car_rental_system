<?php
require_once("../includes/session.php");
require_once("../config/conn.php");

// ========================
// Summary
// ========================

// จำนวนรถทั้งหมด
$sql = "SELECT COUNT(*) AS total FROM vehicles";
$result = $conn->query($sql);
$totalVehicle = $result->fetch_assoc()["total"];

// จำนวนลูกค้าทั้งหมด
$sql = "SELECT COUNT(*) AS total FROM customers";
$result = $conn->query($sql);
$totalCustomer = $result->fetch_assoc()["total"];

// จำนวนการจองทั้งหมด
$sql = "SELECT COUNT(*) AS total FROM bookings";
$result = $conn->query($sql);
$totalBooking = $result->fetch_assoc()["total"];

// รายได้รวม
$sql = "SELECT IFNULL(SUM(amount),0) AS total
        FROM payments
        WHERE status='paid'";
$result = $conn->query($sql);
$totalIncome = $result->fetch_assoc()["total"];

include("../includes/header.php");
include("../includes/navbar.php");
?>

<div class="d-flex">

<?php include("../includes/sidebar.php"); ?>

<div class="container-fluid p-4">

<h2 class="mb-4">

<i class="bi bi-speedometer2"></i>

Dashboard

</h2>

<div class="alert alert-success">

ยินดีต้อนรับ

<b><?= $_SESSION["name"] ?></b>

</div>

<div class="row">

<!-- รถทั้งหมด -->

<div class="col-md-3 mb-4">

<div class="card bg-primary text-white shadow">

<div class="card-body">

<div class="d-flex justify-content-between align-items-center">

<div>

<h6>รถทั้งหมด</h6>

<h2><?= $totalVehicle ?></h2>

</div>

<i class="bi bi-car-front-fill display-5"></i>

</div>

</div>

</div>

</div>

<!-- ลูกค้าทั้งหมด -->

<div class="col-md-3 mb-4">

<div class="card bg-success text-white shadow">

<div class="card-body">

<div class="d-flex justify-content-between align-items-center">

<div>

<h6>ลูกค้าทั้งหมด</h6>

<h2><?= $totalCustomer ?></h2>

</div>

<i class="bi bi-people-fill display-5"></i>

</div>

</div>

</div>

</div>

<!-- การจอง -->

<div class="col-md-3 mb-4">

<div class="card bg-warning shadow">

<div class="card-body">

<div class="d-flex justify-content-between align-items-center">

<div>

<h6>การจองทั้งหมด</h6>

<h2><?= $totalBooking ?></h2>

</div>

<i class="bi bi-calendar-check-fill display-5"></i>

</div>

</div>

</div>

</div>

<!-- รายได้ -->

<div class="col-md-3 mb-4">

<div class="card bg-danger text-white shadow">

<div class="card-body">

<div class="d-flex justify-content-between align-items-center">

<div>

<h6>รายได้รวม</h6>

<h4><?= number_format($totalIncome,2) ?> บาท</h4>

</div>

<i class="bi bi-cash-stack display-5"></i>

</div>

</div>

</div>

</div>

</div>

<div class="row">
    <!-- ==========================
     รายการจองล่าสุด
========================== -->

<div class="col-lg-8">

<div class="card shadow mb-4">

<div class="card-header bg-primary text-white">

<h5 class="mb-0">

<i class="bi bi-calendar-check-fill"></i>

รายการจองล่าสุด

</h5>

</div>

<div class="card-body">

<?php

$sql = "SELECT
            b.id,
            c.full_name,
            v.brand,
            v.model,
            b.start_date,
            b.end_date,
            b.status
        FROM bookings b
        INNER JOIN customers c
            ON b.renter_id = c.id
        INNER JOIN vehicles v
            ON b.vehicle_id = v.id
        ORDER BY b.created_at DESC
        LIMIT 5";

$result = $conn->query($sql);

?>

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead>

<tr>

<th>ลูกค้า</th>

<th>รถ</th>

<th>รับรถ</th>

<th>คืนรถ</th>

<th>สถานะ</th>

</tr>

</thead>

<tbody>

<?php while($row = $result->fetch_assoc()){ ?>

<tr>

<td>

<?= htmlspecialchars($row["full_name"]) ?>

</td>

<td>

<?= htmlspecialchars($row["brand"]) ?>

<?= htmlspecialchars($row["model"]) ?>

</td>

<td>

<?= date("d/m/Y",strtotime($row["start_date"])) ?>

</td>

<td>

<?= date("d/m/Y",strtotime($row["end_date"])) ?>

</td>

<td>

<?php

switch($row["status"]){

case "pending":

echo '<span class="badge bg-warning text-dark">Pending</span>';

break;

case "confirmed":

echo '<span class="badge bg-primary">Confirmed</span>';

break;

case "in_progress":

echo '<span class="badge bg-info">In Progress</span>';

break;

case "completed":

echo '<span class="badge bg-success">Completed</span>';

break;

case "cancelled":

echo '<span class="badge bg-danger">Cancelled</span>';

break;

default:

echo '<span class="badge bg-secondary">'.$row["status"].'</span>';

}

?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<!-- ==========================
     รถที่กำลังถูกเช่า
========================== -->

<div class="col-lg-4">

<div class="card shadow mb-4">

<div class="card-header bg-success text-white">

<h5 class="mb-0">

<i class="bi bi-car-front-fill"></i>

รถที่กำลังถูกเช่า

</h5>

</div>

<div class="card-body">

<?php

$sql = "SELECT
            brand,
            model,
            plate_no
        FROM vehicles
        WHERE status='rented'
        ORDER BY brand";

$result = $conn->query($sql);

if($result->num_rows > 0){

?>

<ul class="list-group">

<?php while($car = $result->fetch_assoc()){ ?>

<li class="list-group-item">

<strong>

<?= htmlspecialchars($car["brand"]) ?>

<?= htmlspecialchars($car["model"]) ?>

</strong>

<br>

<small class="text-muted">

ทะเบียน :

<?= htmlspecialchars($car["plate_no"]) ?>

</small>

</li>

<?php } ?>

</ul>

<?php }else{ ?>

<div class="text-center text-muted">

<i class="bi bi-check-circle-fill display-4"></i>

<p class="mt-3 mb-0">

ไม่มีรถที่กำลังถูกเช่า

</p>

</div>

<?php } ?>

</div>

</div>

</div>

</div>

<div class="row">
    <!-- ==========================
     กราฟสรุปข้อมูล
========================== -->

<div class="col-md-6">

<div class="card shadow mb-4">

<div class="card-header bg-info text-white">

<h5 class="mb-0">

<i class="bi bi-pie-chart-fill"></i>

สถานะรถ

</h5>

</div>

<div class="card-body">

<?php

$sql = "SELECT status, COUNT(*) AS total
        FROM vehicles
        GROUP BY status";

$result = $conn->query($sql);

$labels = [];
$data = [];

while($row = $result->fetch_assoc()){

    $labels[] = ucfirst($row["status"]);
    $data[] = $row["total"];

}

?>

<canvas id="vehicleChart"></canvas>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card shadow mb-4">

<div class="card-header bg-danger text-white">

<h5 class="mb-0">

<i class="bi bi-bar-chart-fill"></i>

รายได้รายเดือน

</h5>

</div>

<div class="card-body">

<?php

$sql = "SELECT
            MONTH(paid_at) AS month,
            SUM(amount) AS total
        FROM payments
        WHERE status='paid'
        GROUP BY MONTH(paid_at)
        ORDER BY MONTH(paid_at)";

$result = $conn->query($sql);

$monthLabel = [];
$monthIncome = [];

$monthName = [
    "",
    "ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.",
    "ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค."
];

while($row = $result->fetch_assoc()){

    $monthLabel[] = $monthName[$row["month"]];
    $monthIncome[] = $row["total"];

}

?>

<canvas id="incomeChart"></canvas>

</div>

</div>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

// =======================
// Pie Chart : Vehicle Status
// =======================

new Chart(document.getElementById("vehicleChart"),{

type:"pie",

data:{

labels:<?= json_encode($labels) ?>,

datasets:[{

data:<?= json_encode($data) ?>

}]

},

options:{

responsive:true,

plugins:{

legend:{

position:"bottom"

}

}

}

});

// =======================
// Bar Chart : Income
// =======================

new Chart(document.getElementById("incomeChart"),{

type:"bar",

data:{

labels:<?= json_encode($monthLabel) ?>,

datasets:[{

label:"รายได้ (บาท)",

data:<?= json_encode($monthIncome) ?>

}]

},

options:{

responsive:true,

scales:{

y:{

beginAtZero:true

}

}

}

});

</script>

<?php include("../includes/footer.php"); ?>