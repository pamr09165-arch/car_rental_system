<?php
require_once("../includes/session.php");
require_once("../config/conn.php");

// ========================
// ดึง Booking ที่ยังไม่มีการชำระเงิน
// ========================

$sql = "SELECT
            b.id,
            c.full_name,
            v.brand,
            v.model,
            v.plate_no,
            b.total_price
        FROM bookings b
        INNER JOIN customers c
            ON b.renter_id = c.id
        INNER JOIN vehicles v
            ON b.vehicle_id = v.id
        LEFT JOIN payments p
            ON b.id = p.booking_id
        WHERE p.booking_id IS NULL
        ORDER BY b.created_at DESC";

$result = $conn->query($sql);

include("../includes/header.php");
include("../includes/navbar.php");
?>

<div class="d-flex">

<?php include("../includes/sidebar.php"); ?>

<div class="container-fluid p-4">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h4 class="mb-0">

<i class="bi bi-credit-card"></i>

เพิ่มการชำระเงิน

</h4>

</div>

<div class="card-body">

<form
action="store.php"
method="POST">

<div class="mb-3">

<label class="form-label">

Booking

</label>

<select
name="booking_id"
id="booking"
class="form-select"
required>

<option value="">

-- เลือกรายการจอง --

</option>

<?php while($row = $result->fetch_assoc()){ ?>

<option

value="<?= $row["id"] ?>"

data-price="<?= $row["total_price"] ?>">

<?= htmlspecialchars($row["full_name"]) ?>

|

<?= htmlspecialchars($row["brand"]) ?>

<?= htmlspecialchars($row["model"]) ?>

|

<?= htmlspecialchars($row["plate_no"]) ?>

</option>

<?php } ?>

</select>

</div>

<div class="mb-3">

<label class="form-label">

จำนวนเงิน (บาท)

</label>

<input
type="number"
step="0.01"
name="amount"
id="amount"
class="form-control"
required>
</div>

<div class="mb-3">

<label class="form-label">

วิธีชำระเงิน

</label>

<select name="method" class="form-select" required>

<option value="">-- เลือกวิธีชำระเงิน --</option>

<option value="cash">เงินสด</option>

<option value="bank_transfer">โอนผ่านธนาคาร</option>

<option value="promptpay">PromptPay</option>

<option value="credit_card">บัตรเครดิต</option>

<option value="wallet">E-Wallet</option>

</select>
</div>

<div class="mb-3">

<label class="form-label">

สถานะ

</label>

<select
name="status"
class="form-select"
required>

<option value="pending">Pending</option>

<option value="paid">Paid</option>

</select>

</div>

<div class="mb-3">

<label class="form-label">

วันที่ชำระเงิน

</label>

<input

type="datetime-local"

name="paid_at"

class="form-control">

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
class="btn btn-primary">

<i class="bi bi-save"></i>

บันทึกข้อมูล

</button>

</div>

</form>

</div>

</div>

</div>

</div>

<script>

$(document).ready(function(){

    $("#booking").on("change", function(){

        let price = $(this).find(":selected").attr("data-price");

        $("#amount").val(price);

    });

});

</script>

<?php include("../includes/footer.php"); ?>