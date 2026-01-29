<?php
session_start();
include_once 'connect.php';

// ส่วนหัว HTML เพื่อโหลด SweetAlert (ต้องมี ไม่งั้นจอขาว)
echo '<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>body{background-color:#2c3e50;font-family:sans-serif;}</style>
</head>
<body>';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // ค้นหา User
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // ตรวจสอบรหัสผ่าน
        if (password_verify($password, $row['password'])) {

            // เก็บ Session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['fullname'] = $row['fullname'];
            $_SESSION['status'] = $row['status'];

            // 👉 กำหนดหน้าปลายทาง (Routing)
            $redirect_url = 'user_dashboard.php'; // ชี้ไปให้ถูกไฟล์

            if ($row['status'] == 'admin') {
                $redirect_url = 'admin/dashboard.php';
            } elseif ($row['status'] == 'shipper') {
                $redirect_url = 'shipper/shipper_dashboard.php';
            }

            // แสดง Popup สวยๆ แล้วเด้งไปหน้าถัดไป
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'เข้าสู่ระบบสำเร็จ!',
                    text: 'ยินดีต้อนรับคุณ " . $row['fullname'] . "',
                    timer: 1500,
                    showConfirmButton: false
                }).then(function() {
                    window.location = '$redirect_url'; 
                });
            </script>";

        } else {
            // รหัสผ่านผิด
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'รหัสผ่านไม่ถูกต้อง',
                    text: 'กรุณาลองใหม่อีกครั้ง'
                }).then(() => { 
                    window.history.back(); 
                });
            </script>";
        }
    } else {
        // ไม่พบชื่อผู้ใช้
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'ไม่พบชื่อผู้ใช้นี้',
                text: 'กรุณาตรวจสอบ Username หรือสมัครสมาชิกใหม่'
            }).then(() => { 
                window.history.back(); 
            });
        </script>";
    }
} else {
    // เข้าผิดทาง
    echo "<script>window.location='login.php';</script>";
}

echo '</body></html>';
?>