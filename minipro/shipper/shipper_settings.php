<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['status'] != 'shipper') {
    header("Location: ../login.php");
    exit();
}
include '../connect.php';

// อัปเดตข้อมูล
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_SESSION['user_id'];
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];

    // อัปเดตรหัสผ่านถ้ามีการกรอก
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "UPDATE users SET fullname='$fullname', phone='$phone', password='$password' WHERE id='$id'";
    } else {
        $sql = "UPDATE users SET fullname='$fullname', phone='$phone' WHERE id='$id'";
    }

    if (mysqli_query($con, $sql)) {
        $_SESSION['fullname'] = $fullname; // อัปเดตชื่อใน Session ทันที
        echo "<script>alert('อัปเดตข้อมูลสำเร็จ!'); window.location='shipper_settings.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด: " . mysqli_error($con) . "');</script>";
    }
}

$user_id = $_SESSION['user_id'];
$query = mysqli_query($con, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Driver Settings - Minipro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg mb-4" style="background-color: #000; border-bottom: 1px solid #333;">
        <div class="container">
            <a class="navbar-brand fw-bold text-white" href="shipper_dashboard.php">MINIPRO <span
                    class="text-danger small">DRIVER</span></a>
            <div class="d-flex align-items-center gap-3">
                <a href="shipper_dashboard.php" class="text-muted text-decoration-none small">กลับหน้างาน</a>
            </div>
        </div>
    </nav>

    <div class="container" style="max-width: 600px;">
        <div class="card shadow-sm border-secondary" style="background-color: var(--secondary-bg);">
            <div class="card-header bg-transparent border-bottom border-secondary p-4 text-center">
                <div
                    style="width:80px; height:80px; background:#333; border-radius:50%; margin:0 auto 15px; display:flex; align-items:center; justify-content:center; border:2px solid var(--accent-color);">
                    <i class="bi bi-truck text-white fs-1"></i>
                </div>
                <h4 class="text-white fw-bold m-0">ข้อมูลพนักงานขับรถ</h4>
                <span class="badge bg-danger mt-2">Verified Driver</span>
            </div>
            <div class="card-body p-4">
                <form method="POST">
                    <div class="mb-3">
                        <label class="text-muted small">ชื่อ-นามสกุล</label>
                        <input type="text" name="fullname" class="form-control bg-dark text-white border-secondary"
                            value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">เบอร์โทรศัพท์ (สำหรับติดต่อลูกค้า)</label>
                        <input type="text" name="phone" class="form-control bg-dark text-white border-secondary"
                            value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                    </div>

                    <hr class="border-secondary my-4">

                    <div class="mb-3">
                        <label class="text-muted small">เปลี่ยนรหัสผ่าน (เว้นว่างไว้ถ้าไม่ต้องการเปลี่ยน)</label>
                        <input type="password" name="password" class="form-control bg-dark text-white border-secondary"
                            placeholder="New Password">
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary w-100 fw-bold">บันทึกข้อมูล</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>