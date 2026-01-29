<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['status'] != 'shipper') {
    header("Location: ../login.php");
    exit();
}
include '../connect.php'; // ถอยหลัง 1 ชั้น
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>History - Minipro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg mb-4" style="background-color: #000; border-bottom: 1px solid #333;">
        <div class="container">
            <a class="navbar-brand fw-bold text-white" href="shipper_dashboard.php">MINIPRO <span
                    class="text-danger small">DRIVER</span></a>
            <a href="shipper_dashboard.php" class="btn btn-sm btn-outline-secondary">กลับหน้าหลัก</a>
        </div>
    </nav>

    <div class="container">
        <h3 class="fw-bold text-white mb-3"><i class="bi bi-clock-history text-danger"></i> ประวัติการส่งงาน (Completed)
        </h3>

        <div class="card shadow-sm border-secondary" style="background-color: var(--secondary-bg);">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">Tracking</th>
                            <th>ผู้รับ</th>
                            <th>วันที่ส่งสำเร็จ</th>
                            <th>สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM shipments WHERE status = 'Delivered' ORDER BY created_at DESC";
                        $result = mysqli_query($con, $sql);

                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr>
                                            <td class="ps-4 text-danger fw-bold"><?php echo $row['tracking_number']; ?></td>
                                            <td class="text-white"><?php echo $row['recipient_name']; ?></td>
                                            <td class="text-muted"><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                                            <td><span class="badge bg-success"><i class="bi bi-check-circle-fill"></i> สำเร็จ</span>
                                            </td>
                                        </tr>
                                    <?php
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center py-4 text-muted'>ยังไม่มีประวัติการส่งงาน</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>