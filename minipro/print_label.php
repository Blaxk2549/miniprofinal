<?php
session_start();
include 'connect.php';

if (!isset($_GET['tracking'])) {
    die("ไม่พบเลขพัสดุ");
}
$tracking = mysqli_real_escape_string($con, $_GET['tracking']);

// ดึงข้อมูลพัสดุ
$sql = "SELECT * FROM shipments WHERE tracking_number = '$tracking'";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    die("ไม่พบข้อมูล");
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Label:
        <?php echo $tracking; ?>
    </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #eee;
            padding: 20px;
        }

        .label-card {
            width: 100%;
            max-width: 500px;
            background: white;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 20px;
            font-family: 'Courier New', monospace;
        }

        .barcode {
            height: 60px;
            background: repeating-linear-gradient(90deg, #000 0, #000 2px, #fff 2px, #fff 4px);
            width: 80%;
            margin: 10px auto;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .no-print {
                display: none;
            }

            .label-card {
                border: none;
            }
        }
    </style>
</head>

<body>
    <div class="text-center mb-3 no-print">
        <button onclick="window.print()" class="btn btn-primary">🖨️ สั่งพิมพ์ (Print)</button>
        <button onclick="window.close()" class="btn btn-secondary">ปิดหน้าต่าง</button>
    </div>

    <div class="label-card">
        <div class="d-flex justify-content-between align-items-center border-bottom border-2 border-dark pb-2 mb-3">
            <h2 class="fw-bold m-0">MINIPRO</h2>
            <div class="text-end">
                <span class="d-block fw-bold">
                    <?php echo strtoupper($row['destination_type']); ?>
                </span>
                <small>
                    <?php echo date('d/m/Y', strtotime($row['created_at'])); ?>
                </small>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12 mb-3">
                <small class="text-muted fw-bold">TO (ผู้รับ):</small>
                <div class="fs-5 fw-bold">
                    <?php echo $row['recipient_name']; ?>
                </div>
                <div>
                    <?php echo $row['recipient_phone']; ?>
                </div>
                <div style="font-size: 1.1rem; line-height: 1.4;">
                    <?php echo nl2br($row['recipient_address']); ?>
                </div>
            </div>
            <div class="col-12 mt-2 pt-2 border-top border-dark">
                <small class="text-muted fw-bold">FROM (ผู้ส่ง):</small>
                <div>
                    <?php echo $_SESSION['fullname']; ?> (User ID:
                    <?php echo $_SESSION['user_id']; ?>)
                </div>
            </div>
        </div>

        <div class="text-center border-top border-2 border-dark pt-3">
            <div class="barcode"></div>
            <h1 class="fw-bold m-0" style="letter-spacing: 2px;">
                <?php echo $tracking; ?>
            </h1>
        </div>

        <div class="row mt-3 text-center small fw-bold">
            <div class="col-4 border-end border-dark">WEIGHT<br>
                <?php echo $row['weight']; ?> KG
            </div>
            <div class="col-4 border-end border-dark">PRICE<br>฿
                <?php echo $row['price']; ?>
            </div>
            <div class="col-4">COD<br>฿0.00</div>
        </div>
    </div>
</body>

</html>