<?php
require_once("../includes/session.php");

include("../includes/header.php");
include("../includes/navbar.php");
?>

<div class="d-flex">

<?php include("../includes/sidebar.php"); ?>

<div class="container-fluid p-4">

    <h2><i class="bi bi-car-front-fill"></i> เพิ่มรถ</h2>

    <hr>

    <form action="store.php" method="POST" enctype="multipart/form-data">

        <div class="row">

            <!-- รูปภาพ -->
            <div class="col-md-12 mb-4 text-center">

                <img
                    id="previewImage"
                    src="../assets/images/no-image.png"
                    class="img-thumbnail mb-3"
                    style="width:250px;height:180px;object-fit:cover;">

                <br>

                <label class="form-label fw-bold">
                    รูปรถ
                </label>

                <input
                    type="file"
                    name="image"
                    id="image"
                    class="form-control"
                    accept=".jpg,.jpeg,.png,.webp">

                <small class="text-muted">
                    รองรับ JPG, PNG, WEBP
                </small>

            </div>

            <!-- Brand -->
            <div class="col-md-6 mb-3">
                <label class="form-label">ยี่ห้อ</label>
                <input type="text" name="brand" class="form-control" required>
            </div>

            <!-- Model -->
            <div class="col-md-6 mb-3">
                <label class="form-label">รุ่น</label>
                <input type="text" name="model" class="form-control" required>
            </div>

            <!-- Year -->
            <div class="col-md-6 mb-3">
                <label class="form-label">ปี</label>
                <input type="number" name="year" class="form-control">
            </div>

            <!-- Plate -->
            <div class="col-md-6 mb-3">
                <label class="form-label">ทะเบียนรถ</label>
                <input type="text" name="plate_no" class="form-control" required>
            </div>

            <!-- Price -->
            <div class="col-md-6 mb-3">
                <label class="form-label">ราคา / วัน</label>
                <input
                    type="number"
                    step="0.01"
                    name="price_per_day"
                    class="form-control"
                    required>
            </div>

            <!-- Deposit -->
            <div class="col-md-6 mb-3">
                <label class="form-label">เงินมัดจำ</label>
                <input
                    type="number"
                    step="0.01"
                    name="deposit_amount"
                    class="form-control"
                    value="0">
            </div>

            <!-- Location -->
            <div class="col-md-6 mb-3">
                <label class="form-label">สถานที่จอด</label>
                <input
                    type="text"
                    name="location"
                    class="form-control">
            </div>

            <!-- Status -->
            <div class="col-md-6 mb-3">

                <label class="form-label">สถานะ</label>

                <select name="status" class="form-select">

                    <option value="available">
                        Available
                    </option>

                    <option value="rented">
                        Rented
                    </option>

                    <option value="maintenance">
                        Maintenance
                    </option>

                </select>

            </div>

        </div>

        <button class="btn btn-success">

            <i class="bi bi-check-circle"></i>

            บันทึกข้อมูล

        </button>

        <a href="index.php" class="btn btn-secondary">

            <i class="bi bi-arrow-left"></i>

            กลับ

        </a>

    </form>

</div>

</div>

<script>

// Preview Image

document
.getElementById("image")
.addEventListener("change",function(e){

    const file=e.target.files[0];

    if(file){

        document
        .getElementById("previewImage")
        .src=URL.createObjectURL(file);

    }

});

</script>

<?php include("../includes/footer.php"); ?>