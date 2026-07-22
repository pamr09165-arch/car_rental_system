<?php
require_once("../includes/session.php");
require_once("../config/conn.php");

$sql = "SELECT
            brand,
            model,
            year,
            plate_no,
            price_per_day,
            deposit_amount,
            location,
            status
        FROM vehicles
        ORDER BY created_at DESC";

$result = $conn->query($sql);

include("../includes/header.php");
include("../includes/navbar.php");
?>

<div class="d-flex">

<?php include("../includes/sidebar.php"); ?>

<div class="container-fluid p-4">

<div class="d-flex justify-content-between align-items-center mb-3">

<h2>

<i class="bi bi-car-front-fill"></i>

รายงานรถทั้งหมด

</h2>

<div>

<button
onclick="window.print()"
class="btn btn-success">

<i class="bi bi-printer"></i>

พิมพ์รายงาน

</button>

<a
href="index.php"
class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

กลับ

</a>

</div>

</div>

<hr>

<div class="table-responsive">

<table
id="vehicleReport"
class="table table-bordered table-hover align-middle">

<thead class="table-dark">

<tr>

<th width="60">#</th>

<th>ยี่ห้อ</th>

<th>รุ่น</th>

<th>ปี</th>

<th>ทะเบียน</th>

<th>ราคา/วัน</th>

<th>มัดจำ</th>

<th>สถานที่</th>

<th>สถานะ</th>

</tr>

</thead>

<tbody>

<?php

$no = 1;

while($row = $result->fetch_assoc()){

?>
<tr>

<td><?= $no++ ?></td>

<td><?= htmlspecialchars($row["brand"]) ?></td>

<td><?= htmlspecialchars($row["model"]) ?></td>

<td><?= htmlspecialchars($row["year"]) ?></td>

<td><?= htmlspecialchars($row["plate_no"]) ?></td>

<td>

<?= number_format($row["price_per_day"],2) ?>

บาท

</td>

<td>

<?= number_format($row["deposit_amount"],2) ?>

บาท

</td>

<td><?= htmlspecialchars($row["location"]) ?></td>

<td>

<?php

switch($row["status"]){

    case "available":
        echo '<span class="badge bg-success">Available</span>';
        break;

    case "rented":
        echo '<span class="badge bg-primary">Rented</span>';
        break;

    case "maintenance":
        echo '<span class="badge bg-warning text-dark">Maintenance</span>';
        break;

    case "inactive":
        echo '<span class="badge bg-danger">Inactive</span>';
        break;

    default:
        echo '<span class="badge bg-secondary">'
            . htmlspecialchars($row["status"])
            . '</span>';
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

<script>

$(document).ready(function(){

    $('#vehicleReport').DataTable({

        pageLength:10,

        language:{

            url:"//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json"

        }

    });

});

</script>

<?php include("../includes/footer.php"); ?>