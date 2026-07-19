<?php
session_start();
require_once("../config/conn.php");

// ตรวจสอบว่ามีการส่งข้อมูลมาหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // ค้นหาผู้ใช้จากอีเมล
    $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        // ตรวจสอบรหัสผ่าน
        if (password_verify($password, $user["password_hash"])) {

            // สร้าง Session
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["role"] = $user["role"];

            header("Location: ../dashboard/index.php");
            exit();

        } else {

            echo "รหัสผ่านไม่ถูกต้อง";

        }

    } else {

        echo "ไม่พบผู้ใช้งาน";

    }

}
?>