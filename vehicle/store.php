<?php
require_once("../includes/session.php");
require_once("../config/conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // รับค่าจากฟอร์ม
    $brand = trim($_POST["brand"]);
    $model = trim($_POST["model"]);
    $year = !empty($_POST["year"]) ? $_POST["year"] : NULL;
    $plate_no = trim($_POST["plate_no"]);
    $price_per_day = $_POST["price_per_day"];
    $deposit_amount = $_POST["deposit_amount"];
    $location = trim($_POST["location"]);
    $status = $_POST["status"];

    // owner_id ใช้ user ที่ Login อยู่
    $owner_id = $_SESSION["user_id"];

    // ตรวจสอบทะเบียนรถซ้ำ
    $check = $conn->prepare("SELECT id FROM vehicles WHERE plate_no = ?");
    $check->bind_param("s", $plate_no);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>
                alert('ทะเบียนรถนี้มีอยู่ในระบบแล้ว');
                window.history.back();
              </script>";
        exit();
    }

    // เพิ่มข้อมูล
    $sql = "INSERT INTO vehicles
            (owner_id, brand, model, year, plate_no, price_per_day, deposit_amount, location, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "sssssddss",
        $owner_id,
        $brand,
        $model,
        $year,
        $plate_no,
        $price_per_day,
        $deposit_amount,
        $location,
        $status
    );

    if ($stmt->execute()) {

        header("Location: index.php?success=1");
        exit();

    } else {

        echo "เกิดข้อผิดพลาด : " . $stmt->error;

    }

}