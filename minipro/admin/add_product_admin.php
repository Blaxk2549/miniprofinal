<?php
session_start();
include '../connect.php';
if ($_POST) {
    $stmt = mysqli_prepare($con, "INSERT INTO products (p_name, p_price, p_qty) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sdi", $_POST['name'], $_POST['price'], $_POST['qty']);
    mysqli_stmt_execute($stmt);
    header("Location: stock.php");
}
?>
<form method="post" style="padding:50px;">
    <h3>เพิ่มสินค้า</h3>
    <input name="name" placeholder="ชื่อ" required><br><br>
    <input name="price" placeholder="ราคา" required><br><br>
    <input name="qty" placeholder="จำนวน" required><br><br>
    <button type="submit">บันทึก</button>
</form>