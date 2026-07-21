<?php
require_once("../includes/session.php");

include("../includes/header.php");
include("../includes/navbar.php");
?>

<div class="d-flex">

<?php include("../includes/sidebar.php"); ?>

<div class="container-fluid p-4">

<h2>
<i class="bi bi-person-plus-fill"></i>
เพิ่มลูกค้า
</h2>

<hr>

<form action="store.php" method="POST" enctype="multipart/form-data">

<div class="row">

<div class="col-md-6 mb-3">
<label class="form-label">ชื่อ-นามสกุล</label>
<input
type="text"
name="full_name"
class="form-control"
required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">เบอร์โทร</label>
<input
type="text"
name="phone"
class="form-control"
required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Email</label>
<input
type="email"
name="email"
class="form-control">
</div>

<div class="col-md-6 mb-3">
<label class="form-label">เลขบัตรประชาชน</label>
<input
type="text"
name="id_card_no"
class="form-control"
maxlength="13"
required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">เลขใบขับขี่</label>
<input
type="text"
name="driver_license_no"
class="form-control">
</div>

<div class="col-md-6 mb-3">
<label class="form-label">รูปบัตรประชาชน</label>
<input
type="file"
name="id_card_image"
id="id_card_image"
class="form-control"
accept=".jpg,.jpeg,.png,.webp">
</div>

<div class="col-md-6 mb-3">
<label class="form-label">รูปใบขับขี่</label>
<input
type="file"
name="license_image"
id="license_image"
class="form-control"
accept=".jpg,.jpeg,.png,.webp">
</div>

<div class="col-md-6 mb-3 text-center">

<img
id="previewCard"
src="../assets/images/no-image.png"
class="img-thumbnail"
style="width:250px;height:180px;object-fit:cover;">

</div>

<div class="col-md-6 mb-3 text-center">

<img
id="previewLicense"
src="../assets/images/no-image.png"
class="img-thumbnail"
style="width:250px;height:180px;object-fit:cover;">

</div>

<div class="col-md-12 mb-3">
<label class="form-label">ที่อยู่</label>
<textarea
name="address"
rows="4"
class="form-control"></textarea>
</div>

</div>

<button class="btn btn-success">
<i class="bi bi-save"></i>
บันทึกข้อมูล
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