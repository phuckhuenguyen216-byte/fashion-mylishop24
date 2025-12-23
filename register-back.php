<?php
session_start();
error_reporting(E_ALL ^ E_DEPRECATED);
require_once '../model/connect.php';
require_once '../model/send_email.php';
if (isset($_POST['submit'])) {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']); // Mã hóa md5 ngay ở đây
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $sql = "INSERT INTO users (fullname, username, password, email, phone, address, role)
            VALUES ('$fullname', '$username', '$password', '$email', '$phone', '$address', 0)";

    $res = mysqli_query($conn, $sql);
    if ($res) {
        header("location:login.php?rs=success");
        exit();
    } else {
        header("location:login.php?rf=fail");
        exit();
    }
}
