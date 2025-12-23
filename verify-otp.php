<?php session_start();
require_once('../model/connect.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Xác minh OTP - MyLiShop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../images/logohong.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css'>
    <script src='../js/wow.js'></script>
    <script type="text/javascript" src="../js/mylishop.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>

<body>

    <div class="container" style="margin-top: 50px;">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="text-center">Xác minh OTP</h3>
                    </div>
                    <div class="panel-body">
                        <?php if (isset($_SESSION['error'])) {
                            echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                            unset($_SESSION['error']);
                        } ?>
                        <form action="" method="POST">
                            <div class="form-group">
                                <label>Nhập mã OTP:</label>
                                <input type="text" name="otp" class="form-control" maxlength="6" required>
                            </div>
                            <div class="form-group">
                                <label>Mật khẩu mới:</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Xác nhận mật khẩu mới:</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Xác nhận</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $otp = mysqli_real_escape_string($conn, $_POST['otp']);
        $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
        $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

        if ($new_password !== $confirm_password) {
            $_SESSION['error'] = 'Mật khẩu xác nhận không khớp!';
            header("location: verify-otp.php");
            exit();
        }

        $email = $_SESSION['reset_email'];
        $sql_user = "SELECT id FROM users WHERE email = '$email'";
        $res_user = mysqli_query($conn, $sql_user);
        $user = mysqli_fetch_assoc($res_user);
        $user_id = $user['id'];

        $sql_otp = "SELECT * FROM otp_codes WHERE user_id = $user_id AND otp = '$otp' AND expires_at > NOW()";
        $res_otp = mysqli_query($conn, $sql_otp);
        if (mysqli_num_rows($res_otp) > 0) {
            // Cập nhật mật khẩu mới
            $hashed_pass = md5($new_password);
            $sql_update = "UPDATE users SET password = '$hashed_pass' WHERE id = $user_id";
            mysqli_query($conn, $sql_update);

            // Xóa OTP cũ
            $sql_delete = "DELETE FROM otp_codes WHERE user_id = $user_id";
            mysqli_query($conn, $sql_delete);

            unset($_SESSION['reset_email']);
            echo "<script>alert('Đặt lại mật khẩu thành công! Vui lòng đăng nhập.'); window.location = 'login.php';</script>";
        } else {
            $_SESSION['error'] = 'Mã OTP sai hoặc hết hạn!';
            header("location: verify-otp.php");
        }
    }
    ?>

</body>

</html>