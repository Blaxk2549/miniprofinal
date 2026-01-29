<?php include 'connect.php'; ?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Track & Trace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container min-vh-100 d-flex flex-column justify-content-center align-items-center">

        <div class="text-center mb-5">
            <h1 class="fw-bold text-white display-4">TRACK & <span class="text-danger">TRACE</span></h1>
            <p class="text-muted">ระบบติดตามพัสดุ Minipro Logistics</p>
        </div>

        <div class="card border-secondary bg-dark p-4 shadow-lg w-100" style="max-width: 600px;">
            <form method="GET">
                <div class="input-group input-group-lg">
                    <input type="text" name="track_id" class="form-control bg-black text-white border-secondary"
                        placeholder="กรอกเลขพัสดุ (เช่น TH-123456)"
                        value="<?php echo isset($_GET['track_id']) ? htmlspecialchars($_GET['track_id']) : ''; ?>"
                        required>
                    <button class="btn btn-primary fw-bold px-4" type="submit">ค้นหา</button>
                </div>
            </form>
        </div>

        <?php
        if (isset($_GET['track_id'])) {
            $track_id = mysqli_real_escape_string($con, $_GET['track_id']);
            $sql = "SELECT * FROM shipments WHERE tracking_number = '$track_id'";
            $result = mysqli_query($con, $sql);

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $statusColor = match ($row['status']) { 'Pending' => 'bg-warning', 'In Transit' => 'bg-info', 'Delivered' => 'bg-success', 'Cancelled' => 'bg-danger', default => 'bg-secondary'};
                ?>
                <div class="card mt-4 border-secondary bg-dark text-white w-100" style="max-width: 600px;">
                    <div class="card-body p-4 text-center">
                        <h3 class="text-primary fw-bold">
                            <?php echo $row['tracking_number']; ?>
                        </h3>
                        <span class="badge <?php echo $statusColor; ?> fs-6 mb-3">
                            <?php echo $row['status']; ?>
                        </span>
                        <hr class="border-secondary">
                        <div class="row text-start mt-3">
                            <div class="col-6"><small class="text-muted">ผู้รับ:</small><br>
                                <?php echo $row['recipient_name']; ?>
                            </div>
                            <div class="col-6 text-end"><small class="text-muted">วันที่ส่ง:</small><br>
                                <?php echo date('d/m/Y', strtotime($row['created_at'])); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            } else {
                echo "<div class='alert alert-danger mt-4 w-100 text-center' style='max-width: 600px;'>❌ ไม่พบเลขพัสดุนี้ในระบบ</div>";
            }
        }
        ?>

        <div class="mt-5">
            <a href="user_dashboard.php" class="text-muted text-decoration-none"><i class="bi bi-arrow-left"></i>
                กลับหน้าหลัก</a>
        </div>
    </div>
</body>

</html>