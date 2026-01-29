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
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../style.css">
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
            <a href="manage_users.php" class="sidebar-link active"><i class="bi bi-people me-2"></i> Users</a>
            <a href="stock.php" class="sidebar-link"><i class="bi bi-box-seam me-2"></i> Stock</a>
            <a href="settings.php" class="sidebar-link"><i class="bi bi-gear me-2"></i> Settings</a>
        </nav>
        <div class="p-4 border-top border-secondary">
            <a href="../logout.php" class="btn btn-outline-danger w-100 btn-sm">ออกจากระบบ</a>
        </div>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary"><i class="bi bi-people"></i> จัดการสมาชิก</h2>
            <button class="btn btn-primary shadow" data-bs-toggle="modal" data-bs-target="#addUserModal"><i
                    class="bi bi-person-plus"></i> เพิ่มสมาชิก</button>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4">Username</th>
                            <th>ชื่อ-สกุล</th>
                            <th>สถานะ</th>
                            <th class="text-end pe-4">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $res = mysqli_query($con, "SELECT * FROM users");
                        while ($row = mysqli_fetch_assoc($res)) {
                            echo "<tr>
                                <td class='ps-4 fw-bold text-white'>{$row['username']}</td>
                                <td>{$row['fullname']}</td>
                                <td><span class='badge bg-dark border border-secondary text-light'>{$row['status']}</span></td>
                                <td class='text-end pe-4'>
                                    <a href='edit_user.php?id={$row['id']}' class='btn btn-sm btn-warning text-dark me-1'><i class='bi bi-pencil-square'></i></a>
                                    
                                    <a href='user_actions.php?action=delete&id={$row['id']}' class='btn btn-sm btn-outline-danger' onclick='return confirm(\"ลบ?\")'><i class='bi bi-trash'></i></a>
                                </td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="user_actions.php" method="POST" class="modal-content bg-dark text-white border-secondary">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title">เพิ่มสมาชิกใหม่</h5><button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-2"><label>Username</label><input name="username"
                            class="form-control bg-secondary text-white border-0" required></div>
                    <div class="mb-2"><label>Password</label><input name="password"
                            class="form-control bg-secondary text-white border-0" required></div>
                    <div class="mb-2"><label>Full Name</label><input name="fullname"
                            class="form-control bg-secondary text-white border-0" required></div>
                    <div class="mb-2"><label>Phone</label><input name="phone"
                            class="form-control bg-secondary text-white border-0" required></div>
                    <div class="mb-2"><label>Status</label>
                        <select name="status" class="form-select bg-secondary text-white border-0">
                            <option value="user">User</option>
                            <option value="shipper">Shipper</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-secondary"><button type="submit"
                        class="btn btn-primary w-100">บันทึก</button></div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>