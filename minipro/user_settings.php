<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['status'] != 'user') {
    header("Location: login.php");
    exit();
}
include 'connect.php';

// อัปเดตข้อมูลเมื่อกดบันทึก
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_SESSION['user_id'];
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];

    // ถ้ามีการเปลี่ยนรหัสผ่าน
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "UPDATE users SET fullname='$fullname', phone='$phone', password='$password' WHERE id='$id'";
    } else {
        $sql = "UPDATE users SET fullname='$fullname', phone='$phone' WHERE id='$id'";
    }

    if (mysqli_query($con, $sql)) {
        $_SESSION['fullname'] = $fullname; // อัปเดต session
        echo "<script>alert('บันทึกข้อมูลสำเร็จ!'); window.location='user_settings.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด');</script>";
    }
}

// ดึงข้อมูลล่าสุดมาแสดง
$user_id = $_SESSION['user_id'];
$query = mysqli_query($con, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Settings - Minipro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg sticky-top mb-4" style="background-color: #000; border-bottom: 1px solid #333;">
        <div class="container">
            <a class="navbar-brand fw-bold text-white" href="user_dashboard.php">MINIPRO <span
                    class="text-danger small">CUSTOMER</span></a>
            <div class="d-flex align-items-center gap-3">
                <a href="user_dashboard.php" class="text-muted text-decoration-none small">กลับหน้าหลัก</a>
                <a href="logout.php" class="btn btn-sm btn-outline-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container" style="max-width: 800px;">
        <div class="card shadow-sm border-secondary" style="background-color: var(--secondary-bg);">
            <div class="card-header bg-transparent border-bottom border-secondary p-4">
                <h4 class="text-white fw-bold m-0"><i class="bi bi-person-gear text-danger"></i> ตั้งค่าบัญชีผู้ใช้</h4>
            </div>
            <div class="card-body p-4">
                <form method="POST">
                    <div class="row g-4">
                        <div class="col-md-4 text-center border-end border-secondary">
                            <div class="mb-3">
                                <div
                                    style="width:100px; height:100px; background:#333; border-radius:50%; margin:0 auto; display:flex; align-items:center; justify-content:center; border:2px solid var(--accent-color);">
                                    <span class="fs-1 text-white">
                                        <?php echo mb_substr($user['fullname'], 0, 1); ?>
                                    </span>
                                </div>
                            </div>
                            <h5 class="text-white">
                                <?php echo $user['username']; ?>
                            </h5>
                            <span class="badge bg-danger">Customer Member</span>
                        </div>

                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="text-muted small">ชื่อ-นามสกุล</label>
                                <input type="text" name="fullname"
                                    class="form-control bg-dark text-white border-secondary"
                                    value="<?php echo $user['fullname']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">เบอร์โทรศัพท์</label>
                                <input type="text" name="phone" class="form-control bg-dark text-white border-secondary"
                                    value="<?php echo $user['phone']; ?>" required>
                            </div>

                            <hr class="border-secondary my-4">

                            <div class="mb-3">
                                <label class="text-muted small">รหัสผ่านใหม่ (เว้นว่างไว้ถ้าไม่ต้องการเปลี่ยน)</label>
                                <input type="password" name="password"
                                    class="form-control bg-dark text-white border-secondary" placeholder="New Password">
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary px-4">บันทึกการเปลี่ยนแปลง</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>