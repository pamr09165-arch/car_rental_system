<?php
require_once("../includes/session.php");
require_once("../config/conn.php");

if (!isset($_GET["id"])) {
    header("Location:index.php");
    exit();
}

$id = $_GET["id"];

// ========================
// ดึงข้อมูล Payment
// ========================

$sql = "SELECT
            p.*,
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
        WHERE p.id=?
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location:index.php");
    exit();
}

$row = $result->fetch_assoc();

include("../includes/header.php");
include("../includes/navbar.php");
?>

<div class="d-flex">

<?php include("../includes/sidebar.php"); ?>

<div class="container-fluid p-4">

<div class="card shadow">

<div class="card-header bg-warning text-dark">

<h4 class="mb-0">

<i class="bi bi-pencil-square"></i>

แก้ไขการชำระเงิน

</h4>

</div>

<div class="card-body">

<form
action="update.php"
method="POST">

<input
type="hidden"
name="id"
value="<?= $row["id"] ?>">

<div class="mb-3">

<label class="form-label">

รายการจอง

</label>

<input
type="text"
class="form-control"
value="<?= htmlspecialchars($row["full_name"]) ?> |
<?= htmlspecialchars($row["brand"]) ?>
<?= htmlspecialchars($row["model"]) ?>
(<?= htmlspecialchars($row["plate_no"]) ?>)"
readonly>

</div>

<div class="mb-3">

<label class="form-label">

จำนวนเงิน (บาท)

</label>

<input

type="number"

step="0.01"

name="amount"

class="form-control"

value="<?= $row["amount"] ?>"

required>

</div>

<div class="mb-3">

<label class="form-label">

วิธีชำระเงิน

</label>

<select
name="method"
class="form-select"
required>
<option value="cash"
<?= ($row["method"]=="cash")?"selected":""; ?>>
เงินสด
</option>

<option value="bank_transfer"
<?= ($row["method"]=="bank_transfer")?"selected":""; ?>>
โอนผ่านธนาคาร
</option>

<option value="promptpay"
<?= ($row["method"]=="promptpay")?"selected":""; ?>>
PromptPay
</option>

<option value="credit_card"
<?= ($row["method"]=="credit_card")?"selected":""; ?>>
บัตรเครดิต
</option>

<option value="wallet"
<?= ($row["method"]=="wallet")?"selected":""; ?>>
E-Wallet
</option>

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

<option
value="pending"
<?= ($row["status"]=="pending")?"selected":""; ?>>

Pending

</option>

<option
value="paid"
<?= ($row["status"]=="paid")?"selected":""; ?>>

Paid

</option>

</select>

</div>

<div class="mb-3">

<label class="form-label">

วันที่ชำระเงิน

</label>

<input

type="datetime-local"

name="paid_at"

class="form-control"

value="<?= !empty($row["paid_at"]) ? date('Y-m-d\TH:i', strtotime($row["paid_at"])) : '' ?>">

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
class="btn btn-warning">

<i class="bi bi-save"></i>

บันทึกการแก้ไข

</button>

</div>

</form>

</div>

</div>

</div>

</div>

<?php include("../includes/footer.php"); ?>