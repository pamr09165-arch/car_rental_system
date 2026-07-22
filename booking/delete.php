<?php
require_once("../includes/session.php");
require_once("../config/conn.php");

if (!isset($_GET["id"])) {
    header("Location:index.php");
    exit();
}

$id = $_GET["id"];

// ======================
// ดึงข้อมูล Booking
// ======================

$sql = "SELECT vehicle_id, status
        FROM bookings
        WHERE id=?
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location:index.php");
    exit();
}

$booking = $result->fetch_assoc();

$vehicle_id = $booking["vehicle_id"];
$status = $booking["status"];

// ======================
// Begin Transaction
// ======================

$conn->begin_transaction();

try {

    // ----------------------
    // ถ้ายังไม่คืนรถ
    // ให้เปลี่ยนรถเป็น Available
    // ----------------------

    if ($status == "pending" || $status == "confirmed") {

        $update = $conn->prepare("
            UPDATE vehicles
            SET status='available'
            WHERE id=?
        ");

        $update->bind_param("s", $vehicle_id);

        if (!$update->execute()) {
            throw new Exception($update->error);
        }

    }

    // ----------------------
    // ลบ Booking
    // ----------------------

    $delete = $conn->prepare("
        DELETE FROM bookings
        WHERE id=?
    ");

    $delete->bind_param("s", $id);

    if (!$delete->execute()) {
        throw new Exception($delete->error);
    }

    // ----------------------

    $conn->commit();

    header("Location:index.php?delete=1");
    exit();

} catch (Exception $e) {

    $conn->rollback();

    die("Error : " . $e->getMessage());

}