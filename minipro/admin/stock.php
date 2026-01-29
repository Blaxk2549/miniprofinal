<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// 1. เช็คสิทธิ์ Admin
if (!isset($_SESSION['user_id']) || $_SESSION['status'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
include '../connect.php';

// 2. ส่วนจัดการ: ลบสินค้า
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    mysqli_query($con, "DELETE FROM products WHERE product_id = '$id'");
    header("Location: stock.php"); // รีเฟรชหน้า
    exit();
}

// 3. ส่วนจัดการ: เพิ่มสินค้า
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $box_name = $_POST['box_name'];
    $dimensions = $_POST['dimensions'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $sql = "INSERT INTO products (box_name, dimensions, price, stock) VALUES ('$box_name', '$dimensions', '$price', '$stock')";
    mysqli_query($con, $sql);
    header("Location: stock.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Stock Management</title>
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
            <a href="manage_users.php" class="sidebar-link"><i class="bi bi-people me-2"></i> Users</a>
            <a href="stock.php" class="sidebar-link active"><i class="bi bi-box-seam me-2"></i> Stock</a>
            <a href="settings.php" class="sidebar-link"><i class="bi bi-gear me-2"></i> Settings</a>
        </nav>
        <div class="p-4 border-top border-secondary">
            <a href="../logout.php" class="btn btn-outline-danger w-100 btn-sm">ออกจากระบบ</a>
        </div>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary"><i class="bi bi-box-seam"></i> คลังสินค้า</h2>
            <button class="btn btn-primary shadow" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="bi bi-plus-lg"></i> เพิ่มสินค้า
            </button>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-4">ชื่อสินค้า (ขนาดกล่อง)</th>
                                <th>ขนาด</th>
                                <th>ราคา</th>
                                <th>จำนวนคงเหลือ</th>
                                <th class="text-end pe-4">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = mysqli_query($con, "SELECT * FROM products ORDER BY product_id ASC");
                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    // จัดการสีถ้าของใกล้มด
                                    $stockColor = ($row['stock'] < 10) ? 'bg-danger' : 'bg-success';
                                    
                                    echo "<tr>
                                        <td class='ps-4 fw-bold text-white'>{$row['box_name']}</td>
                                        <td class='text-muted'>{$row['dimensions']}</td>
                                        <td class='text-warning fw-bold'>฿" . number_format($row['price'], 2) . "</td>
                                        <td><span class='badge {$stockColor}'>{$row['stock']} ชิ้น</span></td>
                                        <td class='text-end pe-4'>
                                            <a href='stock.php?action=delete&id={$row['product_id']}' class='btn btn-sm btn-outline-danger' onclick='return confirm(\"ยืนยันการลบ?\")'><i class='bi bi-trash'></i></a>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center py-5 text-muted'>ยังไม่มีสินค้าในคลัง</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" class="modal-content bg-dark text-white border-secondary">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title">เพิ่มสินค้าใหม่</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="mb-3">
                        <label>ชื่อสินค้า / ขนาดกล่อง</label>
                        <input type="text" name="box_name" class="form-control bg-secondary text-white border-0" placeholder="เช่น กล่อง Size A" required>
                    </div>
                    
                    <div class="mb-3">
                        <label>ขนาด (กว้าง x ยาว x สูง)</label>
                        <input type="text" name="dimensions" class="form-control bg-secondary text-white border-0" placeholder="เช่น 14 x 20 x 6 cm" required>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label>ราคา (บาท)</label>
                            <input type="number" step="0.01" name="price" class="form-control bg-secondary text-white border-0" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label>จำนวนสต็อก</label>
                            <input type="number" name="stock" class="form-control bg-secondary text-white border-0" value="100" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>