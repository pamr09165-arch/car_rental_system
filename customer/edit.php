<?php
require_once("../includes/session.php");
require_once("../config/conn.php");

if (!isset($_GET["id"])) {
    header("Location:index.php");
    exit();
}

$id = $_GET["id"];

$stmt = $conn->prepare("SELECT * FROM customers WHERE id=?");
$stmt->bind_param("s", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location:index.php");
    exit();
}

$row = $result->fetch_assoc();

$idCardImage = "../assets/images/no-image.png";
if (!empty($row["id_card_image"]) && file_exists("../uploads/customers/".$row["id_card_image"])) {
    $idCardImage = "../uploads/customers/".$row["id_card_image"];
}

$licenseImage = "../assets/images/no-image.png";
if (!empty($row["license_image"]) && file_exists("../uploads/customers/".$row["license_image"])) {
    $licenseImage = "../uploads/customers/".$row["license_image"];
}

include("../includes/header.php");
include("../includes/navbar.php");
?>

<div class="d-flex">

<?php include("../includes/sidebar.php"); ?>

<div class="container-fluid p-4">

<h2>
<i class="bi bi-pencil-square"></i>
แก้ไขข้อมูลลูกค้า
</h2>

<hr>

<form action="update.php" method="POST" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?= $row["id"] ?>">

<div class="row">

<div class="col-md-6 text-center mb-3">

<img
id="previewCard"
src="<?= $idCardImage ?>"
class="img-thumbnail mb-3"
style="width:250px;height:180px;object-fit:cover;">

<label class="form-label">รูปบัตรประชาชน</label>

<input
type="file"
name="id_card_image"
id="id_card_image"
class="form-control"
accept=".jpg,.jpeg,.png,.webp">

</div>

<div class="col-md-6 text-center mb-3">

<img
id="previewLicense"
src="<?= $licenseImage ?>"
class="img-thumbnail mb-3"
style="width:250px;height:180px;object-fit:cover;">

<label class="form-label">รูปใบขับขี่</label>

<input
type="file"
name="license_image"
id="license_image"
class="form-control"
accept=".jpg,.jpeg,.png,.webp">

</div>

<div class="col-md-6 mb-3">

<label>ชื่อ-นามสกุล</label>

<input
type="text"
name="full_name"
class="form-control"
value="<?= htmlspecialchars($row["full_name"]) ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label>เบอร์โทร</label>

<input
type="text"
name="phone"
class="form-control"
value="<?= htmlspecialchars($row["phone"]) ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label>Email</label>

<input
type="email"
name="email"
class="form-control"
value="<?= htmlspecialchars($row["email"]) ?>">

</div>

<div class="col-md-6 mb-3">

<label>เลขบัตรประชาชน</label>

<input
type="text"
name="id_card_no"
class="form-control"
value="<?= htmlspecialchars($row["id_card_no"]) ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label>เลขใบขับขี่</label>

<input
type="text"
name="driver_license_no"
class="form-control"
value="<?= htmlspecialchars($row["driver_license_no"]) ?>">

</div>

<div class="col-md-12 mb-3">

<label>ที่อยู่</label>

<textarea
name="address"
class="form-control"
rows="4"><?= htmlspecialchars($row["address"]) ?></textarea>

</div>

<button class="btn btn-success">
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

document.getElementById("id_card_image").addEventListener("change",function(e){

const file=e.target.files[0];

if(file){

document.getElementById("previewCard").src=URL.createObjectURL(file);

}

});

document.getElementById("license_image").addEventListener("change",function(e){

const file=e.target.files[0];

if(file){

document.getElementById("previewLicense").src=URL.createObjectURL(file);

}

});

</script>

<?php include("../includes/footer.php"); ?> 