<?php
session_start();
include 'connect.php';

// 1. เช็คสิทธิ์ User
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$fullname = $_SESSION['fullname'] ?? $_SESSION['name'] ?? 'คุณลูกค้า'; 

// --- ส่วนคำนวณตัวเลข (Stats) ---
$q_all = mysqli_query($con, "SELECT COUNT(*) as total FROM shipments WHERE user_id = '$user_id'");
$count_all = (mysqli_num_rows($q_all) > 0) ? mysqli_fetch_assoc($q_all)['total'] : 0;

$q_active = mysqli_query($con, "SELECT COUNT(*) as total FROM shipments WHERE user_id = '$user_id' AND status IN ('Pending', 'Shipping', 'In Transit')");
$count_active = (mysqli_num_rows($q_active) > 0) ? mysqli_fetch_assoc($q_active)['total'] : 0;

$q_success = mysqli_query($con, "SELECT COUNT(*) as total FROM shipments WHERE user_id = '$user_id' AND status = 'Delivered'");
$count_success = (mysqli_num_rows($q_success) > 0) ? mysqli_fetch_assoc($q_success)['total'] : 0;
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard - Minipro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .navbar-custom {
            background-color: #000;
            border-bottom: 1px solid #333;
            padding: 15px 0;
        }
        .nav-link { color: #aaa; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { color: #fff; }
        .stat-card {
            background-color: var(--secondary-bg, #fff);
            border: 1px solid #ddd;
            border-radius: 10px;
            transition: transform 0.2s;
        }
        .stat-card:hover { transform: translateY(-5px); border-color: #dc3545; }
        .icon-box {
            width: 50px; height: 50px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 10px; font-size: 1.5rem;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-white fs-4" href="user_dashboard.php">
                MINI<span class="text-danger">PRO</span>
            </a>

            <button class="navbar-toggler border-secondary bg-light" type="button" data-bs-toggle="collapse"
                data-bs-target="#userNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="userNavbar">
                <ul class="navbar-nav ms-auto align-items-center gap-3">
                    <li class="nav-item">
                        <a href="user_dashboard.php" class="nav-link active">หน้าหลัก</a>
                    </li>
                    <li class="nav-item">
                        <a href="track.php" class="nav-link">ติดตามพัสดุ</a>
                    </li>
                    <li class="nav-item">
                        <a href="booking.php" class="nav-link">ส่งของ</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white fw-bold d-flex align-items-center gap-2" href="#"
                            role="button" data-bs-toggle="dropdown">
                            <div class="bg-danger rounded-circle d-flex justify-content-center align-items-center"
                                style="width: 35px; height: 35px;">
                                <?php echo mb_substr($fullname, 0, 1); ?>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg">
                            <li><span class="dropdown-item-text text-muted small">สวัสดี, <?php echo $fullname; ?></span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php">ออกจากระบบ</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stat-card p-3 d-flex align-items-center gap-3">
                    <div class="icon-box bg-primary bg-opacity-25 text-primary"><i class="bi bi-box-seam"></i></div>
                    <div>
                        <div class="text-muted small">พัสดุทั้งหมด</div>
                        <h3 class="fw-bold m-0"><?php echo $count_all; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card p-3 d-flex align-items-center gap-3">
                    <div class="icon-box bg-warning bg-opacity-25 text-warning"><i class="bi bi-truck"></i></div>
                    <div>
                        <div class="text-muted small">กำลังจัดส่ง</div>
                        <h3 class="fw-bold m-0"><?php echo $count_active; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card p-3 d-flex align-items-center gap-3">
                    <div class="icon-box bg-success bg-opacity-25 text-success"><i class="bi bi-check-lg"></i></div>
                    <div>
                        <div class="text-muted small">ส่งสำเร็จ</div>
                        <h3 class="fw-bold m-0"><?php echo $count_success; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold"><i class="bi bi-clock-history text-danger"></i> รายการล่าสุด</h4>
        </div>

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">Tracking ID</th>
                            <th>ผู้รับปลายทาง</th>
                            <th>ประเภท</th>
                            <th>สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM shipments WHERE user_id = '$user_id' ORDER BY id DESC";
                        $result = mysqli_query($con, $sql);

                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $statusCheck = strtolower($row['status']);
                                
                                // 1. กำหนดสีปุ่ม
                                $statusClass = match ($statusCheck) {
                                    'pending' => 'bg-warning text-dark',
                                    'shipping', 'in transit' => 'bg-info text-dark',
                                    'delivered' => 'bg-success text-white',
                                    'cancelled' => 'bg-danger text-white',
                                    default => 'bg-secondary'
                                };

                                // 2. ✅ แปลงเป็นภาษาไทย (จุดที่เพิ่มให้ครับ)
                                $statusThai = match ($statusCheck) {
                                    'pending' => 'รอจัดส่ง',
                                    'shipping', 'in transit' => 'กำลังจัดส่ง',
                                    'delivered' => 'ส่งสำเร็จ',
                                    'cancelled' => 'ยกเลิก',
                                    default => $row['status'] // ถ้าไม่เข้าเงื่อนไข ให้โชว์คำเดิม
                                };
                                
                                $tracking_show = isset($row['tracking_number']) ? $row['tracking_number'] : "TH".str_pad($row['id'], 6, '0', STR_PAD_LEFT);
                                ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-danger"><?php echo $tracking_show; ?></div>
                                        <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></small>
                                    </td>
                                    <td>
                                        <?php echo $row['recipient_name']; ?><br>
                                    </td>
                                    <td>
                                        <?php echo ($row['destination_type'] == 'domestic') ?
                                            '<span class="badge bg-secondary">ในประเทศ</span>' :
                                            '<span class="badge bg-primary">ต่างประเทศ</span>'; ?>
                                    </td>
                                    <td><span class="badge <?php echo $statusClass; ?>"><?php echo $statusThai; ?></span></td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center py-5 text-muted'>ยังไม่มีรายการส่งพัสดุ</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>