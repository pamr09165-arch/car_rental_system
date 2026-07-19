<?php
require_once("../includes/session.php");

include("../includes/header.php");
include("../includes/navbar.php");
?>

<div class="d-flex">

<?php include("../includes/sidebar.php"); ?>

<div class="container-fluid p-4">

<h2>🚗 เพิ่มรถ</h2>

<hr>

<form action="store.php" method="POST">

    <div class="row">

        <div class="col-md-6 mb-3">
            <label class="form-label">ยี่ห้อ</label>
            <input type="text" name="brand" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">รุ่น</label>
            <input type="text" name="model" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">ปี</label>
            <input type="number" name="year" class="form-control">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">ทะเบียนรถ</label>
            <input type="text" name="plate_no" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">ราคา / วัน</label>
            <input type="number" step="0.01" name="price_per_day" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">เงินมัดจำ</label>
            <input type="number" step="0.01" name="deposit_amount" class="form-control" value="0">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">สถานที่จอด</label>
            <input type="text" name="location" class="form-control">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">สถานะ</label>
            <select name="status" class="form-select">
                <option value="available">Available</option>
                <option value="rented">Rented</option>
                <option value="maintenance">Maintenance</option>
            </select>
        </div>

    </div>

    <button class="btn btn-success">
        💾 บันทึกข้อมูล
    </button>

    <a href="index.php" class="btn btn-secondary">
        ยกเลิก
    </a>

</form>

</div>

</div>

<?php include("../includes/footer.php"); ?>