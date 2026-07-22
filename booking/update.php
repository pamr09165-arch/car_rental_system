<?php
require_once("../includes/session.php");
require_once("../config/conn.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location:index.php");
    exit();
}

// ========================
// รับค่าจากฟอร์ม
// ========================

$id = $_POST["id"];

$renter_id = $_POST["renter_id"];
$vehicle_id = $_POST["vehicle_id"];

$start_date = $_POST["start_date"];
$end_date = $_POST["end_date"];

$pickup_location = trim($_POST["pickup_location"]);
$return_location = trim($_POST["return_location"]);

$total_price = $_POST["total_price"];
$status = $_POST["status"];

// ========================
// ตรวจสอบวันที่
// ========================

if (strtotime($end_date) < strtotime($start_date)) {

    echo "<script>

    alert('วันคืนรถต้องมากกว่าหรือเท่ากับวันรับรถ');

    history.back();

    </script>";

    exit();

}

// ========================
// ดึงข้อมูลเดิม
// ========================

$old = $conn->prepare("

SELECT

vehicle_id

FROM bookings

WHERE id=?

LIMIT 1

");

$old->bind_param("s", $id);
$old->execute();

$oldResult = $old->get_result();

if ($oldResult->num_rows == 0) {

    header("Location:index.php");
    exit();

}

$oldBooking = $oldResult->fetch_assoc();

$oldVehicle = $oldBooking["vehicle_id"];

// ========================
// Transaction
// ========================

$conn->begin_transaction();

try {

    // --------------------
    // Update Booking
    // --------------------

    $sql = "

    UPDATE bookings

    SET

    renter_id=?,
    vehicle_id=?,
    start_date=?,
    end_date=?,
    pickup_location=?,
    return_location=?,
    total_price=?,
    status=?

    WHERE id=?

    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(

        "ssssssdss",

        $renter_id,
        $vehicle_id,
        $start_date,
        $end_date,
        $pickup_location,
        $return_location,
        $total_price,
        $status,
        $id

    );

    if (!$stmt->execute()) {

        throw new Exception($stmt->error);

    }

    // --------------------
    // ถ้าเปลี่ยนรถ
    // --------------------

    if ($oldVehicle != $vehicle_id) {

        // รถเก่า -> available

        $q1 = $conn->prepare("

        UPDATE vehicles

        SET status='available'

        WHERE id=?

        ");

        $q1->bind_param("s", $oldVehicle);

        if (!$q1->execute()) {

            throw new Exception($q1->error);

        }

        // รถใหม่ -> rented

        $q2 = $conn->prepare("

        UPDATE vehicles

        SET status='rented'

        WHERE id=?

        ");

        $q2->bind_param("s", $vehicle_id);

        if (!$q2->execute()) {

            throw new Exception($q2->error);

        }

    }

    // --------------------
    // ถ้าคืนรถแล้ว
    // --------------------

    if ($status == "completed") {

        $q3 = $conn->prepare("

        UPDATE vehicles

        SET status='available'

        WHERE id=?

        ");

        $q3->bind_param("s", $vehicle_id);

        if (!$q3->execute()) {

            throw new Exception($q3->error);

        }

    }

    // --------------------
    // ถ้ายังไม่คืน
    // --------------------

    if ($status == "pending" || $status == "confirmed") {

        $q4 = $conn->prepare("

        UPDATE vehicles

        SET status='rented'

        WHERE id=?

        ");

        $q4->bind_param("s", $vehicle_id);

        if (!$q4->execute()) {

            throw new Exception($q4->error);

        }

    }

    // --------------------

    $conn->commit();

    header("Location:index.php?update=1");
    exit();

} catch (Exception $e) {

    $conn->rollback();

    die("Error : " . $e->getMessage());

}