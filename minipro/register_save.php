<?php
session_start();
include 'connect.php';

$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$fullname = $_POST['name'];
$phone = $_POST['phone'];
$status = 'user';

$check = mysqli_query($con, "SELECT * FROM users WHERE username = '$username'");
if (mysqli_num_rows($check) > 0) {
    echo "<script>alert('Username นี้มีคนใช้แล้ว'); window.history.back();</script>";
    exit();
}

$sql = "INSERT INTO users (username, password, fullname, phone, status) VALUES ('$username', '$password', '$fullname', '$phone', '$status')";
if (mysqli_query($con, $sql)) {
    echo "<script>alert('สมัครสมาชิกสำเร็จ!'); window.location='login.php';</script>";
} else {
    echo "Error: " . mysqli_error($con);
}
?>