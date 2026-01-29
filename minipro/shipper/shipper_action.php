<?php
session_start();
// แก้ Path ตรงนี้: ต้องถอยหลัง 1 ชั้น (../)
include '../connect.php';

// ป้องกันคนอื่นเข้าใช้นอกจาก Shipper
if (!isset($_SESSION['user_id']) || $_SESSION['status'] != 'shipper') {
    die("Access Denied");
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];

    $sql = "UPDATE shipments SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "si", $status, $id);

    if (mysqli_stmt_execute($stmt)) {
        // สำเร็จ -> เด้งกลับ
        header("Location: shipper_dashboard.php");
    } else {
        echo "Error: " . mysqli_error($con);
    }
} else {
    header("Location: shipper_dashboard.php");
}
?>