<?php
// 1. เปิดแสดง Error ทั้งหมด (จะได้รู้ว่าพังตรงไหน)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// 2. เช็คสิทธิ์ Admin
if (!isset($_SESSION['user_id']) || $_SESSION['status'] != 'admin') {
    die("<h2 style='color:red; text-align:center; margin-top:50px;'>⛔ Access Denied: คุณไม่มีสิทธิ์เข้าถึงหน้านี้</h2><br><a href='../login.php'>กลับหน้า Login</a>");
}

// 3. เชื่อมต่อฐานข้อมูล (ถอยหลัง 1 ชั้น ../)
if (file_exists('../connect.php')) {
    include_once '../connect.php';
} else {
    die("<h2 style='color:red;'>❌ หาไฟล์ connect.php ไม่เจอ!</h2><p>ตรวจสอบว่าไฟล์ <b>connect.php</b> อยู่ที่โฟลเดอร์หน้าบ้าน (../) หรือไม่</p>");
}

// เช็คตัวแปร $con
if (!isset($con)) {
    die("<h2 style='color:red;'>❌ ไม่พบตัวแปรการเชื่อมต่อฐานข้อมูล ($con)</h2><p>ตรวจสอบไฟล์ connect.php ว่าใช้ตัวแปรชื่อ <b>\$con</b> หรือ <b>\$conn</b></p>");
}

$fullname = $_SESSION['fullname'];

// --- ส่วนดึงข้อมูล Stats ---
// (ใช้ @ นำหน้าเพื่อกัน Error เล็กน้อยๆ ถ้าระบบยังไม่สมบูรณ์)

// นับ User ทั้งหมด
$sql_all = "SELECT COUNT(*) as total FROM users";
$res_all = mysqli_query($con, $sql_all);
$total_users = ($res_all) ? mysqli_fetch_assoc($res_all)['total'] : 0;

// นับลูกค้า (User)
$sql_cust = "SELECT COUNT(*) as total FROM users WHERE status = 'user'";
$res_cust = mysqli_query($con, $sql_cust);
$total_cust = ($res_cust) ? mysqli_fetch_assoc($res_cust)['total'] : 0;

// นับคนส่งของ (Shipper)
$sql_ship = "SELECT COUNT(*) as total FROM users WHERE status = 'shipper'";
$res_ship = mysqli_query($con, $sql_ship);
$total_ship = ($res_ship) ? mysqli_fetch_assoc($res_ship)['total'] : 0;

// ดึง User ล่าสุด 5 คน
$sql_recent = "SELECT * FROM users ORDER BY id DESC LIMIT 5";
$res_recent = mysqli_query($con, $sql_recent);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../style.css">
    <style>
        /* Admin Sidebar Style */
        .sidebar-logistics {
            width: 260px;
            height: 100vh;
            position: fixed;
            background: #0f172a;
            color: white;
            transition: 0.3s;
            z-index: 1000;
        }

        .main-content {
            margin-left: 260px;
            padding: 2rem;
            background: #f8fafc;
            min-height: 100vh;
        }

        .sidebar-link {
            color: #94a3b8;
            padding: 12px 20px;
            display: block;
            text-decoration: none;
            border-left: 3px solid transparent;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.05);
            border-left-color: #f97316;
        }

        @media (max-width: 992px) {
            .sidebar-logistics {
                transform: translateX(-100%);
            }

            .sidebar-logistics.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    <button class="btn btn-primary d-lg-none position-fixed top-0 start-0 m-3 z-3"
        onclick="document.querySelector('.sidebar-logistics').classList.toggle('show')"><i
            class="bi bi-list"></i></button>

    <div class="sidebar-logistics d-flex flex-column">
        <div class="p-4 mb-4">
            <h4 class="fw-bold text-white mb-0"><i class="bi bi-shield-lock-fill text-warning"></i> AdminPanel</h4>
        </div>
        <nav class="flex-grow-1">
            <a href="dashboard.php" class="sidebar-link active"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
            <a href="manage_users.php" class="sidebar-link"><i class="bi bi-people me-2"></i> Users</a>
            <a href="stock.php" class="sidebar-link"><i class="bi bi-box-seam me-2"></i> Stock</a>
            <a href="settings.php" class="sidebar-link"><i class="bi bi-gear me-2"></i> Settings</a>
        </nav>
        <div class="p-4 border-top border-secondary">
            <div class="text-white-50 small mb-2">Logged in as:</div>
            <div class="fw-bold text-white mb-3"><?php echo htmlspecialchars($fullname); ?></div>
            <a href="../logout.php" class="btn btn-outline-danger w-100 btn-sm">Logout</a>
        </div>
    </div>

    <div class="main-content">
        <h2 class="fw-bold text-primary mb-4">Dashboard Overview</h2>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card p-3 border-0 shadow-sm border-start border-4 border-primary">
                    <p class="text-muted small fw-bold mb-1">ALL USERS</p>
                    <h2 class="fw-bold text-primary"><?php echo $total_users; ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 border-0 shadow-sm border-start border-4 border-success">
                    <p class="text-muted small fw-bold mb-1">CUSTOMERS</p>
                    <h2 class="fw-bold text-success"><?php echo $total_cust; ?></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 border-0 shadow-sm border-start border-4 border-info">
                    <p class="text-muted small fw-bold mb-1">SHIPPERS</p>
                    <h2 class="fw-bold text-info"><?php echo $total_ship; ?></h2>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">สมาชิกใหม่ล่าสุด</h5>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($res_recent && mysqli_num_rows($res_recent) > 0) {
                            while ($row = mysqli_fetch_assoc($res_recent)) {
                                ?>
                                <tr>
                                    <td class="fw-bold"><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                                    <td>
                                        <?php
                                        $badge = 'bg-secondary';
                                        if ($row['status'] == 'admin')
                                            $badge = 'bg-danger';
                                        elseif ($row['status'] == 'shipper')
                                            $badge = 'bg-info';
                                        elseif ($row['status'] == 'user')
                                            $badge = 'bg-success';
                                        ?>
                                        <span
                                            class="badge <?php echo $badge; ?>"><?php echo strtoupper($row['status']); ?></span>
                                    </td>
                                </tr>
                            <?php
                            }
                        } else {
                            echo "<tr><td colspan='3' class='text-center py-3'>ยังไม่มีข้อมูลสมาชิก</td></tr>";
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