<?php
require_once("../includes/session.php");
require_once("../config/conn.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location:index.php");
    exit();
}

// =======================
// Generate UUID
// =======================
function generateUUID()
{
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff)
    );
}

$id = generateUUID();

// =======================
// รับค่าจากฟอร์ม
// =======================

$renter_id = $_POST["renter_id"];
$vehicle_id = $_POST["vehicle_id"];
$start_date = $_POST["start_date"];
$end_date = $_POST["end_date"];

$pickup_location = trim($_POST["pickup_location"]);
$return_location = trim($_POST["return_location"]);

$total_price = $_POST["total_price"];
$status = $_POST["status"];

// =======================
// ตรวจสอบวันที่
// =======================

if (strtotime($end_date) < strtotime($start_date)) {

    echo "<script>
    alert('วันคืนรถต้องมากกว่าหรือเท่ากับวันรับรถ');
    history.back();
    </script>";

    exit();

}

// =======================
// ตรวจสอบรถซ้ำ
// =======================

$check = $conn->prepare("
SELECT id
FROM bookings
WHERE vehicle_id=?
AND status IN('pending','confirmed')
");

$check->bind_param("s", $vehicle_id);

$check->execute();

if ($check->get_result()->num_rows > 0) {

    echo "<script>

    alert('รถคันนี้ถูกจองแล้ว');

    history.back();

    </script>";

    exit();

}

// =======================
// Begin Transaction
// =======================

$conn->begin_transaction();

try {

    // ----------------------
    // INSERT Booking
    // ----------------------

    $sql = "

    INSERT INTO bookings(

        id,
        renter_id,
        vehicle_id,
        start_date,
        end_date,
        pickup_location,
        return_location,
        total_price,
        status

    )

    VALUES(

        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?

    )

    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(

        "sssssssds",

        $id,
        $renter_id,
        $vehicle_id,
        $start_date,
        $end_date,
        $pickup_location,
        $return_location,
        $total_price,
        $status

    );

    if (!$stmt->execute()) {

        throw new Exception($stmt->error);

    }

    // ----------------------
    // เปลี่ยนสถานะรถ
    // ----------------------

    $update = $conn->prepare("

        UPDATE vehicles

        SET status='rented'

        WHERE id=?

    ");

    $update->bind_param("s", $vehicle_id);

    if (!$update->execute()) {

        throw new Exception($update->error);

    }

    // ----------------------

    $conn->commit();

    header("Location:index.php?success=1");

    exit();

} catch (Exception $e) {

    $conn->rollback();

    die("Error : " . $e->getMessage());

}