<?php
require_once("../includes/session.php");
require_once("../config/conn.php");

$sql = "SELECT
            p.id,
            c.full_name,
            v.brand,
            v.model,
            v.plate_no,
            p.amount,
            p.method,
            p.status,
            p.paid_at
        FROM payments p
        INNER JOIN bookings b
            ON p.booking_id = b.id
        INNER JOIN customers c
            ON b.renter_id = c.id
        INNER JOIN vehicles v
            ON b.vehicle_id = v.id
        ORDER BY p.created_at DESC";

$result = $conn->query($sql);

$totalIncome = 0;

include("../includes/header.php");
include("../includes/navbar.php");
?>

<div class="d-flex">

<?php include("../includes/sidebar.php"); ?>

<div class="container-fluid p-4">

<div class="d-flex justify-content-between align-items-center mb-3">

<h2>

<i class="bi bi-credit-card-fill"></i>

รายงานการชำระเงิน

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
id="paymentReport"
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

</tr>

</thead>

<tbody>

<?php

$no = 1;

while($row = $result->fetch_assoc()){

$totalIncome += $row["amount"];

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
<?= number_format($row["amount"],2) ?> บาท
</td>

<td>

<?php

switch($row["method"]){

    case "cash":
        echo "เงินสด";
        break;

    case "bank_transfer":
        echo "โอนผ่านธนาคาร";
        break;

    case "promptpay":
        echo "PromptPay";
        break;

    case "credit_card":
        echo "บัตรเครดิต";
        break;

    case "wallet":
        echo "E-Wallet";
        break;

    default:
        echo htmlspecialchars($row["method"]);

}

?>

</td>

<td>

<?php

if($row["status"]=="paid"){

echo '<span class="badge bg-success">Paid</span>';

}elseif($row["status"]=="pending"){

echo '<span class="badge bg-warning text-dark">Pending</span>';

}elseif($row["status"]=="failed"){

echo '<span class="badge bg-danger">Failed</span>';

}else{

echo '<span class="badge bg-info">Refunded</span>';

}

?>

</td>

<td>

<?php

if(!empty($row["paid_at"])){

echo date("d/m/Y H:i",strtotime($row["paid_at"]));

}else{

echo "-";

}

?>

</td>

</tr>

<?php } ?>

</tbody>

<tfoot class="table-secondary">

<tr>

<td colspan="4" class="text-end">

<strong>รวมรายรับทั้งหมด</strong>

</td>

<td>

<strong>

<?= number_format($totalIncome,2) ?>

บาท

</strong>

</td>

<td colspan="3"></td>

</tr>

</tfoot>

</table>

</div>

</div>

</div>

<script>

$(document).ready(function(){

$('#paymentReport').DataTable({

language:{

url:"//cdn.datatables.net/plug-ins/1.13.7/i18n/th.json"

},

pageLength:10

});

});

</script>

<?php include("../includes/footer.php"); ?>