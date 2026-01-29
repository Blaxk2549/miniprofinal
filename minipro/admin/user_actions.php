<?php
session_start();
include '../connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['status'] != 'admin')
    exit("Access Denied");

// เพิ่มสมาชิก
if (isset($_POST['action']) && $_POST['action'] == 'add') {
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, password, fullname, phone, status) VALUES ('{$_POST['username']}', '$pass', '{$_POST['fullname']}', '{$_POST['phone']}', '{$_POST['status']}')";
    mysqli_query($con, $sql);
    header("Location: manage_users.php");
}

// ลบสมาชิก
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    mysqli_query($con, "DELETE FROM users WHERE id = " . $_GET['id']);
    header("Location: manage_users.php");
}
?>