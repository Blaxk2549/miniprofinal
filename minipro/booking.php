<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['status'] != 'user') {
    header("Location: login.php");
    exit();
}
include 'connect.php';
$fullname = $_SESSION['fullname'];

// บันทึกข้อมูลเมื่อกดส่ง
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $r_name = $_POST['recipient_name'];
    $r_phone = $_POST['recipient_phone'];
    $r_addr = $_POST['recipient_address'];
    $type = $_POST['destination_type'];
    $weight = $_POST['weight'];

    // สูตรคำนวณราคา (ปรับได้ตามใจชอบ)
    // ในประเทศ: เริ่ม 30 บาท + กิโลละ 20
    // ต่างประเทศ: เริ่ม 500 บาท + กิโลละ 200
    $price = ($type == 'domestic') ? ($weight * 20) + 30 : ($weight * 200) + 500;

    // สุ่มเลข Tracking (TH-xxxxx)
    $prefix = ($type == 'domestic') ? "TH" : "INTER";
    $tracking = $prefix . "-" . strtoupper(substr(md5(time()), 0, 8)); // สุ่มเลขเท่ๆ

    $sql = "INSERT INTO shipments (user_id, tracking_number, recipient_name, recipient_phone, recipient_address, destination_type, weight, price, status) 
            VALUES ('$user_id', '$tracking', '$r_name', '$r_phone', '$r_addr', '$type', '$weight', '$price', 'Pending')";

    if (mysqli_query($con, $sql)) {
        // ส่งเสร็จเด้งไปหน้า Dashboard
        echo "<script>alert('สร้างรายการสำเร็จ! Tracking: $tracking'); window.location='user_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Booking Shipment - Minipro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg sticky-top mb-4" style="background-color: #000; border-bottom: 1px solid #333;">
        <div class="container">
            <a class="navbar-brand fw-bold text-white" href="user_dashboard.php">MINIPRO <span
                    class="text-danger small">BOOKING</span></a>
            <div class="d-flex align-items-center gap-3">
                <a href="user_dashboard.php" class="text-muted text-decoration-none small">ยกเลิก/กลับหน้าหลัก</a>
            </div>
        </div>
    </nav>

    <div class="container pb-5" style="max-width: 900px;">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-white">📦 สร้างรายการส่งพัสดุ</h2>
            <p class="text-muted">กรอกข้อมูลผู้รับและรายละเอียดพัสดุ</p>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm border-secondary" style="background-color: var(--secondary-bg);">
                    <div class="card-body p-4">
                        <form method="post" id="bookingForm">
                            <h5 class="text-white mb-3 border-bottom border-secondary pb-2"><i
                                    class="bi bi-person-lines-fill text-danger"></i> ข้อมูลผู้รับปลายทาง</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="text-muted small">ชื่อผู้รับ</label>
                                    <input type="text" name="recipient_name"
                                        class="form-control bg-dark text-white border-secondary" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">เบอร์โทรศัพท์</label>
                                    <input type="text" name="recipient_phone"
                                        class="form-control bg-dark text-white border-secondary" required>
                                </div>
                                <div class="col-12">
                                    <label class="text-muted small">ที่อยู่จัดส่ง</label>
                                    <textarea name="recipient_address"
                                        class="form-control bg-dark text-white border-secondary" rows="3"
                                        required></textarea>
                                </div>
                            </div>

                            <h5 class="text-white mb-3 border-bottom border-secondary pb-2"><i
                                    class="bi bi-box-seam text-danger"></i> รายละเอียดพัสดุ</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="text-muted small">ประเภทการส่ง</label>
                                    <select name="destination_type" id="dType"
                                        class="form-select bg-dark text-white border-secondary"
                                        onchange="calculatePrice()">
                                        <option value="domestic">🚚 ในประเทศ (Domestic)</option>
                                        <option value="international">✈️ ต่างประเทศ (International)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">น้ำหนัก (กิโลกรัม)</label>
                                    <input type="number" step="0.1" name="weight" id="dWeight"
                                        class="form-control bg-dark text-white border-secondary" required
                                        oninput="calculatePrice()">
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-top border-secondary text-end">
                                <button type="submit" class="btn btn-primary fw-bold px-4 py-2">ยืนยันการส่ง</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-secondary bg-black">
                    <div class="card-body p-4 text-center">
                        <h5 class="text-muted mb-3">สรุปค่าจัดส่ง</h5>
                        <h1 class="display-4 fw-bold text-danger mb-0" id="showPrice">฿0</h1>
                        <p class="text-muted small">บาท (THB)</p>
                        <hr class="border-secondary">
                        <ul class="list-unstyled text-start text-white-50 small">
                            <li><i class="bi bi-check-circle text-success me-2"></i> ประกันสินค้าพื้นฐาน</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i> บริการเข้ารับพัสดุ</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i> ติดตามพัสดุ 24 ชม.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function calculatePrice() {
            let weight = parseFloat(document.getElementById('dWeight').value) || 0;
            let type = document.getElementById('dType').value;
            let price = 0;

            if (type === 'domestic') {
                price = (weight * 20) + 30; // สูตรคำนวณในประเทศ
            } else {
                price = (weight * 200) + 500; // สูตรคำนวณต่างประเทศ
            }
            document.getElementById('showPrice').innerText = '฿' + price.toLocaleString();
        }
    </script>
</body>

</html>