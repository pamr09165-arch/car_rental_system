<?php
require_once("../includes/session.php");
require_once("../config/conn.php");

include("../includes/header.php");
include("../includes/navbar.php");
?>

<div class="d-flex">

<?php include("../includes/sidebar.php"); ?>

<div class="container-fluid p-4">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>

<i class="bi bi-bar-chart-fill"></i>

Report Management

</h2>

</div>

<hr>

<div class="row">

<!-- Booking Report -->

<div class="col-md-6 col-lg-3 mb-4">

<div class="card shadow h-100 border-primary">

<div class="card-body text-center">

<i class="bi bi-calendar-check display-3 text-primary"></i>

<h5 class="mt-3">

รายงานการจอง

</h5>

<p class="text-muted">

แสดงรายการจองทั้งหมด

</p>

<a
href="booking_report.php"
class="btn btn-primary w-100">

<i class="bi bi-eye"></i>

เปิดรายงาน

</a>

</div>

</div>

</div>

<!-- Payment Report -->

<div class="col-md-6 col-lg-3 mb-4">

<div class="card shadow h-100 border-success">

<div class="card-body text-center">

<i class="bi bi-credit-card display-3 text-success"></i>

<h5 class="mt-3">

รายงานการชำระเงิน

</h5>

<p class="text-muted">

สรุปรายรับทั้งหมด

</p>

<a
href="payment_report.php"
class="btn btn-success w-100">

<i class="bi bi-eye"></i>

เปิดรายงาน

</a>

</div>

</div>

</div>
<!-- Vehicle Report -->

<div class="col-md-6 col-lg-3 mb-4">

<div class="card shadow h-100 border-warning">

<div class="card-body text-center">

<i class="bi bi-car-front-fill display-3 text-warning"></i>

<h5 class="mt-3">

รายงานรถ

</h5>

<p class="text-muted">

แสดงข้อมูลรถทั้งหมด

</p>

<a
href="vehicle_report.php"
class="btn btn-warning w-100">

<i class="bi bi-eye"></i>

เปิดรายงาน

</a>

</div>

</div>

</div>

<!-- Customer Report -->

<div class="col-md-6 col-lg-3 mb-4">

<div class="card shadow h-100 border-info">

<div class="card-body text-center">

<i class="bi bi-people-fill display-3 text-info"></i>

<h5 class="mt-3">

รายงานลูกค้า

</h5>

<p class="text-muted">

แสดงข้อมูลลูกค้าทั้งหมด

</p>

<a
href="customer_report.php"
class="btn btn-info w-100">

<i class="bi bi-eye"></i>

เปิดรายงาน

</a>

</div>

</div>

</div>

</div>

</div>

</div>

<?php include("../includes/footer.php"); ?>