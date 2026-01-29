<?php
session_start();
include '../connect.php';

// รับค่า ID ที่ส่งมา
$id = $_GET['id'];

// ดึงข้อมูลเก่ามาโชว์ก่อน
$sql = "SELECT * FROM users WHERE id = '$id'";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);

// ถ้ากดปุ่มบันทึก
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $status = $_POST['status'];

    // อัปเดตข้อมูลลง Database
    $update_sql = "UPDATE users SET fullname='$fullname', phone='$phone', status='$status' WHERE id='$id'";
    
    if (mysqli_query($con, $update_sql)) {
        echo "<script>alert('แก้ไขข้อมูลสำเร็จ!'); window.location='manage_users.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขข้อมูลสมาชิก</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { background-color: #2c3e50; color: white; display: flex; justify-content: center; align-items: center; height: 100vh; }</style>
</head>
<body>

    <div class="card p-4 shadow-lg text-dark" style="width: 400px;">
        <h3 class="fw-bold mb-3 text-center">✏️ แก้ไขข้อมูล</h3>
        <form method="post">
            <div class="mb-3">
                <label>Username (แก้ไขไม่ได้)</label>
                <input type="text" class="form-control bg-light" value="<?php echo $row['username']; ?>" disabled>
            </div>
            <div class="mb-3">
                <label>ชื่อ-นามสกุล</label>
                <input type="text" name="fullname" class="form-control" value="<?php echo $row['fullname']; ?>" required>
            </div>
            <div class="mb-3">
                <label>เบอร์โทร</label>
                <input type="text" name="phone" class="form-control" value="<?php echo $row['phone']; ?>" required>
            </div>
            <div class="mb-3">
                <label>สถานะ (ระดับผู้ใช้งาน)</label>
                <select name="status" class="form-select bg-warning bg-opacity-25 border-warning fw-bold">
                    <option value="user" <?php if($row['status']=='user') echo 'selected'; ?>>User (ลูกค้าทั่วไป)</option>
                    <option value="shipper" <?php if($row['status']=='shipper') echo 'selected'; ?>>Shipper (คนขับรถ)</option>
                    <option value="admin" <?php if($row['status']=='admin') echo 'selected'; ?>>Admin (ผู้ดูแลระบบ)</option>
                </select>
            </div>
            
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                <a href="manage_users.php" class="btn btn-outline-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>

</body>
</html>