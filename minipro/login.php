<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - Minipro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        /* ปรับแต่งเฉพาะหน้า Login ให้อยู่ตรงกลาง */
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .login-card {
            width: 400px;
            background-color: var(--secondary-bg);
            /* สีเทาเข้ม */
            border: 1px solid #333;
            box-shadow: 0 0 20px rgba(211, 47, 47, 0.2);
            /* เงาสีแดงจางๆ */
        }

        .form-control {
            background-color: #121212;
            border: 1px solid #444;
            color: white;
        }

        .form-control:focus {
            background-color: #000;
            border-color: var(--accent-color);
            color: white;
            box-shadow: 0 0 5px rgba(211, 47, 47, 0.5);
        }
    </style>
</head>

<body>
    <div class="card p-5 login-card">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-white">MINIPRO</h3>
            <p class="text-primary small">LOGISTICS SYSTEM</p>
        </div>

        <form action="check_login.php" method="POST">
            <div class="mb-3">
                <label class="text-muted small">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Enter username" required>
            </div>
            <div class="mb-4">
                <label class="text-muted small">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">เข้าสู่ระบบ</button>

            <div class="text-center mt-4">
                <span class="text-muted small">ยังไม่มีบัญชี?</span>
                <a href="register_form.php" class="text-primary text-decoration-none fw-bold">สมัครสมาชิก</a>
            </div>
        </form>
    </div>
</body>

</html>