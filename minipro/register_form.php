<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register - Minipro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .register-card {
            width: 450px;
            background-color: var(--secondary-bg);
            border: 1px solid #333;
            box-shadow: 0 0 20px rgba(211, 47, 47, 0.2);
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
    <div class="card p-5 register-card">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-white">Create Account</h3>
            <p class="text-muted small">สมัครสมาชิกใหม่</p>
        </div>

        <form action="register_save.php" method="POST">
            <div class="mb-3">
                <label class="text-muted small">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="text-muted small">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="text-muted small">Full Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-4">
                <label class="text-muted small">Phone Number</label>
                <input type="text" name="phone" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">ลงทะเบียน</button>

            <div class="text-center mt-4">
                <a href="login.php" class="text-muted text-decoration-none small"><i class="bi bi-arrow-left"></i>
                    กลับไปหน้าเข้าสู่ระบบ</a>
            </div>
        </form>
    </div>
</body>

</html>