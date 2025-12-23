<?php
session_start();
error_reporting(E_ALL ^ E_DEPRECATED);
require_once('../model/connect.php');
require_once('../model/send_email.php'); // Gửi email

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE username = '$username' AND password = md5('$password')";
    $res = mysqli_query($conn, $sql);

    $rows = mysqli_num_rows($res);
    if ($rows > 0) {
        $user = mysqli_fetch_assoc($res);
        $_SESSION['username'] = $username;
        $_SESSION['id-user'] = $user['id'];
        $_SESSION['role'] = $user['role']; // Lưu role vào session (rất quan trọng cho phân quyền)

        // Gửi email chào mừng cho user
        $user_email = $user['email'];
        $subject_user = "Chào mừng bạn đăng nhập vào MyLiShop!";
        $body_user = "<h2>Xin chào $username,</h2><p>Bạn đã đăng nhập thành công vào lúc " . date('d/m/Y H:i:s') . ".</p><p>Chúc bạn mua sắm vui vẻ!</p><p>Trân trọng,<br>MyLiShop Team</p>";
        send_email($user_email, $subject_user, $body_user);

        // Gửi thông báo cho admin (nếu cần)
        $admin_email = 'hoihmy2712@gmail.com'; // Thay bằng email thật của bạn (admin)
        $subject_admin = "Có người đăng nhập: $username";
        $body_admin = "<h2>Thông báo đăng nhập mới</h2><p>Username: $username</p><p>Email: $user_email</p><p>Thời gian: " . date('d/m/Y H:i:s') . "</p><p>IP: " . $_SERVER['REMOTE_ADDR'] . "</p>";
        send_email($admin_email, $subject_admin, $body_admin);

        // PHÂN QUYỀN ĐĂNG NHẬP
        if ($user['role'] == 1) {
            // Là ADMIN → chuyển vào trang quản trị
            header("location: ../admin/product-list.php");
        } else {
            // Là USER thường → chuyển về trang chủ hoặc giỏ hàng
            header("location: ../index.php");
        }
        exit();
    } else {
        $_SESSION['error'] = 'Tên đăng nhập hoặc mật khẩu không hợp lệ!';
        header("location: ../user/login.php?error=wrong");
        exit();
    }
}
