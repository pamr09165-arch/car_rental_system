<?php
require_once("../includes/session.php");
require_once("../config/conn.php");

if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit();
}

$id = $_GET["id"];

$stmt = $conn->prepare("SELECT * FROM vehicles WHERE id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$row = $result->fetch_assoc();

include("../includes/header.php");
include("../includes/navbar.php");
?>

<div class="d-flex">

    <?php include("../includes/sidebar.php"); ?>

    <div class="container-fluid p-4">

        <h2>
            <i class="bi bi-pencil-square"></i>
            แก้ไขข้อมูลรถ
        </h2>

        <hr>

        <form action="update.php" method="POST" enctype="multipart/form-data">

            <!-- ส่ง id ไป update -->
            <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">

            <?php

            $image = "../assets/images/no-image.png";

            if (!empty($row["image"])) {
                $image = "../uploads/vehicles/" . $row["image"];
            }

            ?>

            <div class="row mb-4">

                <div class="col-md-12 text-center">

                    <img
                        id="previewImage"
                        src="<?= htmlspecialchars($image) ?>"
                        class="img-thumbnail mb-3"
                        style="width:250px;height:180px;object-fit:cover;">

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            เปลี่ยนรูปรถ
                        </label>

                        <input
                            type="file"
                            name="image"
                            id="image"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.webp">

                        <small class="text-muted">
                            หากไม่เลือกรูปใหม่ ระบบจะใช้รูปเดิม
                        </small>
                    </div>

                </div>

            </div>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">ยี่ห้อ</label>
                    <input
                        type="text"
                        name="brand"
                        class="form-control"
                        value="<?= htmlspecialchars($row['brand']) ?>"
                        required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">รุ่น</label>
                    <input
                        type="text"
                        name="model"
                        class="form-control"
                        value="<?= htmlspecialchars($row['model']) ?>"
                        required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">ปี</label>
                    <input
                        type="number"
                        name="year"
                        class="form-control"
                        value="<?= htmlspecialchars($row['year']) ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">ทะเบียนรถ</label>
                    <input
                        type="text"
                        name="plate_no"
                        class="form-control"
                        value="<?= htmlspecialchars($row['plate_no']) ?>"
                        required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">ราคา / วัน</label>
                    <input
                        type="number"
                        step="0.01"
                        name="price_per_day"
                        class="form-control"
                        value="<?= htmlspecialchars($row['price_per_day']) ?>"
                        required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">เงินมัดจำ</label>
                    <input
                        type="number"
                        step="0.01"
                        name="deposit_amount"
                        class="form-control"
                        value="<?= htmlspecialchars($row['deposit_amount']) ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">สถานที่จอด</label>
                    <input
                        type="text"
                        name="location"
                        class="form-control"
                        value="<?= htmlspecialchars($row['location']) ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">สถานะ</label>

                    <select
                        name="status"
                        class="form-select">

                        <option value="available"
                            <?= ($row['status'] == "available") ? "selected" : "" ?>>
                            Available
                        </option>

                        <option value="rented"
                            <?= ($row['status'] == "rented") ? "selected" : "" ?>>
                            Rented
                        </option>

                        <option value="maintenance"
                            <?= ($row['status'] == "maintenance") ? "selected" : "" ?>>
                            Maintenance
                        </option>

                    </select>

                </div>

            </div>

            <button type="submit" class="btn btn-success">
                <i class="bi bi-save"></i>
                บันทึกการแก้ไข
            </button>

            <a href="index.php" class="btn btn-secondary">
                ยกเลิก
            </a>

        </form>

    </div>

</div>

<script>
document.getElementById("image").addEventListener("change", function(e){

    const file = e.target.files[0];

    if(file){

        document.getElementById("previewImage").src =
            URL.createObjectURL(file);

    }

});
</script>

<?php include("../includes/footer.php"); ?>