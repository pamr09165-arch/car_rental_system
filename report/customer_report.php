<?php
require_once("../includes/session.php");
require_once("../config/conn.php");

$sql = "SELECT
            full_name,
            phone,
            email,
            id_card_no,
            driver_license_no,
            address
        FROM customers
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

<i class="bi bi-people-fill"></i>

รายงานข้อมูลลูกค้า

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
id="customerReport"
class="table table-bordered table-hover align-middle">

<thead class="table-dark">

<tr>

<th width="60">#</th>

<th>ชื่อ-นามสกุล</th>

<th>เบอร์โทร</th>

<th>Email</th>

<th>เลขบัตรประชาชน</th>

<th>เลขใบขับขี่</th>

<th>ที่อยู่</th>

</tr>

</thead>

<tbody>

<?php

$no = 1;

while($row = $result->fetch_assoc()){

?>
<tr>

<td><?= $no++ ?></td>

<td><?= htmlspecialchars($row["full_name"]) ?></td>

<td><?= htmlspecialchars($row["phone"]) ?></td>

<td><?= htmlspecialchars($row["email"]) ?></td>

<td><?= htmlspecialchars($row["id_card_no"]) ?></td>

<td><?= htmlspecialchars($row["driver_license_no"]) ?></td>

<td><?= htmlspecialchars($row["address"]) ?></td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<script>

$(document).ready(function(){

    $('#customerReport').DataTable({

        pageLength:10,

        language:{

            url:"//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json"

        }

    });

});

</script>

<?php include("../includes/footer.php"); ?>