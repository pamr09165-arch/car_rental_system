<?php
require_once("../includes/session.php");
require_once("../config/conn.php");

$sql = "SELECT
            p.id,
            p.amount,
            p.method,
            p.status,
            p.paid_at,
            c.full_name,
            v.brand,
            v.model,
            v.plate_no
        FROM payments p
        INNER JOIN bookings b
            ON p.booking_id = b.id
        INNER JOIN customers c
            ON b.renter_id = c.id
        INNER JOIN vehicles v
            ON b.vehicle_id = v.id
        ORDER BY p.created_at DESC";

$result = $conn->query($sql);

include("../includes/header.php");
include("../includes/navbar.php");
?>

<div class="d-flex">

<?php include("../includes/sidebar.php"); ?>

<div class="container-fluid p-4">

<div class="d-flex justify-content-between align-items-center mb-3">

<h2>

<i class="bi bi-credit-card"></i>

Payment Management

</h2>

<a href="create.php" class="btn btn-primary">

<i class="bi bi-plus-circle"></i>

เพิ่มการชำระเงิน

</a>

</div>

<hr>

<?php if(isset($_GET["success"])){ ?>

<div class="alert alert-success alert-dismissible fade show">

บันทึกข้อมูลเรียบร้อย

<button
type="button"
class="btn-close"
data-bs-dismiss="alert"></button>

</div>

<?php } ?>

<?php if(isset($_GET["update"])){ ?>

<div class="alert alert-warning alert-dismissible fade show">

แก้ไขข้อมูลเรียบร้อย

<button
type="button"
class="btn-close"
data-bs-dismiss="alert"></button>

</div>

<?php } ?>

<?php if(isset($_GET["delete"])){ ?>

<div class="alert alert-danger alert-dismissible fade show">

ลบข้อมูลเรียบร้อย

<button
type="button"
class="btn-close"
data-bs-dismiss="alert"></button>

</div>

<?php } ?>

<div class="table-responsive">

<table
id="paymentTable"
class="table table-bordered table-hover align-middle">

<thead class="table-dark">

<tr>

<th width="60">#</th>

<th>ลูกค้า</th>

<th>รถ</th>

<th>ทะเบียน</th>

<th>จำนวนเงิน</th>

<th>วิธีชำระ</th>

<th>สถานะ</th>

<th>วันที่ชำระ</th>

<th width="160">จัดการ</th>

</tr>

</thead>

<tbody>

<?php

$no=1;

while($row=$result->fetch_assoc()){

?>

<tr>

<td><?= $no++ ?></td>

<td><?= htmlspecialchars($row["full_name"]) ?></td>

<td>

<?= htmlspecialchars($row["brand"]) ?>

<?= htmlspecialchars($row["model"]) ?>

</td>

<td><?= htmlspecialchars($row["plate_no"]) ?></td>

<td>

<?= number_format($row["amount"],2) ?>

บาท

</td>

<td>

<?= htmlspecialchars($row["method"]) ?>

</td>

<td>

<?php

if($row["status"]=="pending"){

echo '<span class="badge bg-warning">Pending</span>';

}elseif($row["status"]=="paid"){

echo '<span class="badge bg-success">Paid</span>';

}else{

echo '<span class="badge bg-danger">Cancelled</span>';

}

?>

</td>

<td>

<?php

if($row["paid_at"]!=NULL){

echo date("d/m/Y H:i",strtotime($row["paid_at"]));

}else{

echo "-";

}

?>

</td>

<td>
    <a
href="edit.php?id=<?= $row["id"] ?>"
class="btn btn-warning btn-sm">

<i class="bi bi-pencil-square"></i>

</a>

<a
href="#"
onclick="confirmDelete('<?= $row["id"] ?>')"
class="btn btn-danger btn-sm">

<i class="bi bi-trash"></i>

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<script>

$(document).ready(function(){

$('#paymentTable').DataTable({

language:{

url:"//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json"

},

pageLength:10

});

});

function confirmDelete(id){

Swal.fire({

title:'ยืนยันการลบ',

text:'คุณต้องการลบรายการชำระเงินนี้ใช่หรือไม่?',

icon:'warning',

showCancelButton:true,

confirmButtonColor:'#dc3545',

cancelButtonText:'ยกเลิก',

confirmButtonText:'ลบ'

}).then((result)=>{

if(result.isConfirmed){

window.location='delete.php?id='+id;

}

});

}

</script>

<?php include("../includes/footer.php"); ?>