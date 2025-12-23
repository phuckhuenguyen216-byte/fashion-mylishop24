<?php
session_start();
require_once('../model/connect.php');
require_once('../model/send_email.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $res = mysqli_query($conn, $sql);
    if (mysqli_num_rows($res) > 0) {
        $user = mysqli_fetch_assoc($res);
        $user_id = $user['id'];

        // Tạo OTP 6 số ngẫu nhiên
        $otp = rand(100000, 999999);
        $expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        // Lưu OTP vào DB
        $sql_otp = "INSERT INTO otp_codes (user_id, otp, expires_at) VALUES ($user_id, '$otp', '$expires_at')";
        mysqli_query($conn, $sql_otp);

        // Gửi email OTP
        $subject = "Mã OTP đặt lại mật khẩu MyLiShop";
        $body = "<h2>Mã OTP của bạn là: $otp</h2><p>Mã có hiệu lực trong 5 phút. Đừng chia sẻ với ai!</p>";
        if (send_email($email, $subject, $body)) {
            $_SESSION['reset_email'] = $email;
            header("location: verify-otp.php");
            exit();
        } else {
            $_SESSION['error'] = 'Lỗi gửi email! Vui lòng thử lại.';
            header("location: forgot-password.php");
            exit();
        }
    } else {
        $_SESSION['error'] = 'Email không tồn tại!';
        header("location: forgot-password.php");
        exit();
    }
}
