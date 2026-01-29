<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// เช็คสิทธิ์ Shipper
if (!isset($_SESSION['user_id']) || $_SESSION['status'] != 'shipper') {
    header("Location: ../login.php");
    exit();
}

// เชื่อมต่อฐานข้อมูล
if (file_exists('../connect.php')) {
    include '../connect.php';
} else {
    die("<h2 style='color:red'>❌ หาไฟล์ connect.php ไม่เจอ!</h2>");
}

$fullname = $_SESSION['fullname'];
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Shipper Dashboard - Minipro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../style.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg sticky-top mb-4" style="background-color: #000; border-bottom: 1px solid #333;">
        <div class="container">
            <a class="navbar-brand fw-bold text-white" href="#">MINIPRO <span
                    class="text-danger small">DRIVER</span></a>

            <button class="navbar-toggler border-secondary" type="button" data-bs-toggle="collapse"
                data-bs-target="#shipNav">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>

            <div class="collapse navbar-collapse" id="shipNav">
                <ul class="navbar-nav ms-auto align-items-center gap-3">
                    <li class="nav-item">
                        <a href="shipper_dashboard.php" class="nav-link text-white active fw-bold"><i
                                class="bi bi-list-task text-danger"></i> งานที่ต้องส่ง</a>
                    </li>
                    <li class="nav-item">
                        <a href="shipper_history.php" class="nav-link text-muted"><i class="bi bi-clock-history"></i>
                            ประวัติงาน</a>
                    </li>
                    <li class="nav-item">
                        <a href="shipper_settings.php" class="nav-link text-muted"><i class="bi bi-gear"></i>
                            ตั้งค่า</a>
                    </li>
                    <li class="nav-item d-none d-lg-block">
                        <div class="vr text-secondary mx-2"></div>
                    </li>
                    <li class="nav-item">
                        <span class="text-white small me-2">คนขับ: <?php echo htmlspecialchars($fullname); ?></span>
                        <a href="../logout.php" class="btn btn-sm btn-outline-danger">ออกกะ (Logout)</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-white"><i class="bi bi-truck text-danger"></i> งานรอจัดส่ง (Active Jobs)</h2>
            <span class="badge bg-danger">LIVE STATUS</span>
        </div>

        <div class="card shadow-sm border-secondary" style="background-color: var(--secondary-bg);">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">Tracking ID</th>
                            <th>ข้อมูลผู้รับ</th>
                            <th>ที่อยู่จัดส่ง & นำทาง</th>
                            <th>สถานะ</th>
                            <th class="text-end pe-4">อัปเดตงาน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM shipments WHERE status != 'Delivered' AND status != 'Cancelled' ORDER BY created_at ASC";
                        $result = mysqli_query($con, $sql);

                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                // แปลงสถานะให้เช็คง่ายๆ (ตัวเล็กตัวใหญ่ได้หมด)
                                $statusCheck = strtolower($row['status']); 
                                
                                // ตั้งสีปุ่ม
                                $statusColor = ($statusCheck == 'pending') ? 'bg-warning text-dark' : 'bg-info text-dark';

                                // สร้างลิ้งค์ Google Maps
                                $mapLink = "https://www.google.com/maps/search/?api=1&query=" . urlencode($row['recipient_address']);
                                ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-danger"><?php echo $row['tracking_number']; ?></div>
                                        <small class="text-muted">
                                            <?php echo ($row['destination_type'] == 'domestic') ? 'ในประเทศ' : 'ต่างประเทศ'; ?>
                                        </small>
                                    </td>
                                    <td class="text-white">
                                        <i class="bi bi-person-fill text-muted"></i> <?php echo $row['recipient_name']; ?> <br>
                                        <small class="text-muted"><i class="bi bi-telephone"></i> 
                                            <a href="tel:<?php echo $row['recipient_phone']; ?>" class="text-decoration-none text-muted">
                                                <?php echo $row['recipient_phone']; ?>
                                            </a>
                                        </small>
                                    </td>
                                    <td class="text-white-50">
                                        <div style="max-width: 250px; white-space: normal;">
                                            <?php echo $row['recipient_address']; ?>
                                        </div>
                                        <div class="mt-2">
                                            <a href="<?php echo $mapLink; ?>" target="_blank"
                                                class="btn btn-sm btn-outline-light" style="font-size: 0.75rem;">
                                                <i class="bi bi-geo-alt-fill text-danger"></i> นำทาง
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $statusColor; ?>"><?php echo $row['status']; ?></span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <?php 
                                        // ✅ จุดที่แก้: เช็คครอบคลุมทั้ง 'pending', 'Pending'
                                        if ($statusCheck == 'pending') { 
                                        ?>
                                            <a href="shipper_action.php?id=<?php echo $row['id']; ?>&status=In Transit"
                                                class="btn btn-sm btn-primary fw-bold" onclick="return confirm('ยืนยันรับงานนี้?')">
                                                <i class="bi bi-box-seam"></i> รับงาน
                                            </a>

                                        <?php 
                                        // ✅ จุดที่แก้: เช็คครอบคลุมทั้ง 'in transit', 'shipping' (เผื่อ DB ใช้คำไหนปุ่มก็ขึ้นหมด)
                                        } elseif ($statusCheck == 'in transit' || $statusCheck == 'shipping') { 
                                        ?>
                                            <a href="shipper_action.php?id=<?php echo $row['id']; ?>&status=Delivered"
                                                class="btn btn-sm btn-success fw-bold"
                                                onclick="return confirm('ยืนยันส่งของสำเร็จ?')">
                                                <i class="bi bi-check-circle"></i> ส่งสำเร็จ
                                            </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center py-5 text-muted'>🎉 ไม่มีงานค้างส่งในขณะนี้ พักผ่อนได้!</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>