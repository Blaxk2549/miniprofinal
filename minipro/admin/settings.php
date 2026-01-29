<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['status'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../connect.php';
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>System Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../style.css">
    <style>
        /* ปรับแต่ง Switch ให้เป็นสีแดง */
        .form-check-input:checked {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .settings-card {
            background: #1e1e1e;
            border: 1px solid #333;
        }

        .nav-pills .nav-link.active {
            background-color: var(--accent-color);
        }

        .nav-pills .nav-link {
            color: #b0b0b0;
        }

        .nav-pills .nav-link:hover {
            color: #fff;
        }

        .avatar-circle {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #d32f2f, #000);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            margin: 0 auto;
            border: 3px solid #333;
        }
    </style>
</head>

<body>
    <button class="btn btn-primary d-lg-none position-fixed top-0 start-0 m-3 z-3"
        onclick="document.querySelector('.sidebar-logistics').classList.toggle('show')"><i
            class="bi bi-list"></i></button>

    <div class="sidebar-logistics d-flex flex-column">
        <div class="p-4 mb-4 text-center">
            <h4 class="fw-bold text-danger mb-0">MINIPRO <span class="text-white small">ADMIN</span></h4>
        </div>
        <nav class="flex-grow-1">
            <a href="dashboard.php" class="sidebar-link"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
            <a href="manage_users.php" class="sidebar-link"><i class="bi bi-people me-2"></i> Users</a>
            <a href="stock.php" class="sidebar-link"><i class="bi bi-box-seam me-2"></i> Stock</a>
            <a href="settings.php" class="sidebar-link active"><i class="bi bi-gear me-2"></i> Settings</a>
        </nav>
        <div class="p-4 border-top border-secondary">
            <a href="../logout.php" class="btn btn-outline-danger w-100 btn-sm">ออกจากระบบ</a>
        </div>
    </div>

    <div class="main-content">
        <h2 class="fw-bold text-primary mb-4"><i class="bi bi-sliders"></i> ตั้งค่าระบบ</h2>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card settings-card shadow-sm text-center p-4 h-100">
                    <div class="avatar-circle mb-3 shadow">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <h4 class="text-white fw-bold mb-1"><?php echo $_SESSION['fullname']; ?></h4>
                    <p class="text-primary mb-3">Administrator</p>
                    <div class="badge bg-dark border border-secondary p-2 mb-4">
                        <i class="bi bi-circle-fill text-success small me-1"></i> Online Status
                    </div>

                    <div class="text-start">
                        <label class="text-muted small fw-bold mb-2">QUICK ACTIONS</label>
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-light btn-sm text-start"><i class="bi bi-envelope me-2"></i>
                                เช็คกล่องข้อความ</button>
                            <button class="btn btn-outline-light btn-sm text-start"><i
                                    class="bi bi-shield-lock me-2"></i> บันทึกกิจกรรม (Logs)</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card settings-card shadow-sm">
                    <div class="card-header bg-transparent border-bottom border-secondary p-3">
                        <ul class="nav nav-pills card-header-pills" id="settingsTab" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#account"><i
                                        class="bi bi-person-gear"></i> บัญชีผู้ใช้</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#system"><i
                                        class="bi bi-hdd-network"></i> ระบบ</button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-4">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="account">
                                <h5 class="text-white mb-4">แก้ไขข้อมูลส่วนตัว</h5>
                                <form>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label text-muted">Username</label>
                                            <input type="text" class="form-control bg-dark text-white border-secondary"
                                                value="<?php echo $_SESSION['username']; ?>" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-muted">ตำแหน่ง</label>
                                            <input type="text" class="form-control bg-dark text-white border-secondary"
                                                value="Super Admin" readonly>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label text-muted">ชื่อ-นามสกุล</label>
                                            <input type="text" class="form-control bg-dark text-white border-secondary"
                                                value="<?php echo $_SESSION['fullname']; ?>">
                                        </div>
                                        <div class="col-12 mt-4">
                                            <h6 class="text-white border-bottom border-secondary pb-2 mb-3">
                                                เปลี่ยนรหัสผ่าน</h6>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-muted">รหัสผ่านใหม่</label>
                                            <input type="password"
                                                class="form-control bg-dark text-white border-secondary">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-muted">ยืนยันรหัสผ่าน</label>
                                            <input type="password"
                                                class="form-control bg-dark text-white border-secondary">
                                        </div>
                                        <div class="col-12 mt-3 text-end">
                                            <button type="button" class="btn btn-secondary me-2">รีเซ็ต</button>
                                            <button type="button" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="system">
                                <h5 class="text-white mb-4">การตั้งค่าระบบ (System Config)</h5>

                                <div class="mb-4">
                                    <label class="form-label text-muted d-block mb-3">สถานะเซิร์ฟเวอร์</label>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="siteToggle" checked>
                                        <label class="form-check-label text-white" for="siteToggle">เปิดใช้งานเว็บไซต์
                                            (Online)</label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="maintenanceToggle">
                                        <label class="form-check-label text-white"
                                            for="maintenanceToggle">โหมดปิดปรับปรุง (Maintenance Mode)</label>
                                    </div>
                                </div>

                                <div class="alert alert-dark border-secondary d-flex align-items-center" role="alert">
                                    <i class="bi bi-info-circle-fill text-primary fs-4 me-3"></i>
                                    <div>
                                        <strong class="text-white">System Info:</strong><br>
                                        <small class="text-muted">PHP Version: <?php echo phpversion(); ?> | Server:
                                            Apache</small>
                                    </div>
                                </div>

                                <div class="text-end mt-3">
                                    <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i> ล้าง Cache
                                        ระบบ</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>