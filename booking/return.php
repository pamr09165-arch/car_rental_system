<?php
require_once("../includes/session.php");
require_once("../config/conn.php");

if (!isset($_GET["id"])) {
    header("Location:index.php");
    exit();
}

$booking_id = $_GET["id"];

// ===============================
// ดึงข้อมูลการจอง
// ===============================

$sql = "SELECT vehicle_id,status
        FROM bookings
        WHERE id=?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $booking_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {

    header("Location:index.php");
    exit();

}

$row = $result->fetch_assoc();

$vehicle_id = $row["vehicle_id"];

// ถ้าคืนแล้ว ไม่ต้องคืนซ้ำ
if ($row["status"] == "completed") {

    header("Location:index.php?returned=1");
    exit();

}

// ===============================
// Begin Transaction
// ===============================

$conn->begin_transaction();

try {

    // -----------------------------
    // เปลี่ยนสถานะ Booking
    // -----------------------------

    $updateBooking = $conn->prepare("

        UPDATE bookings

        SET status='completed'

        WHERE id=?

    ");

    $updateBooking->bind_param("s", $booking_id);

    if (!$updateBooking->execute()) {

        throw new Exception($updateBooking->error);

    }

    // -----------------------------
    // เปลี่ยนสถานะรถ
    // -----------------------------

    $updateVehicle = $conn->prepare("

        UPDATE vehicles

        SET status='available'

        WHERE id=?

    ");

    $updateVehicle->bind_param("s", $vehicle_id);

    if (!$updateVehicle->execute()) {

        throw new Exception($updateVehicle->error);

    }

    // -----------------------------

    $conn->commit();

    header("Location:index.php?return=1");
    exit();

} catch (Exception $e) {

    $conn->rollback();

    die("Error : " . $e->getMessage());

}