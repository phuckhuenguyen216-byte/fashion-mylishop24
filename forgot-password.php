<?php session_start();
require_once('../model/connect.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Quên mật khẩu - MyLiShop</title>
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
                        <h3 class="text-center">Quên mật khẩu</h3>
                    </div>
                    <div class="panel-body">
                        <?php if (isset($_SESSION['error'])) {
                            echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                            unset($_SESSION['error']);
                        } ?>
                        <form action="send-otp.php" method="POST">
                            <div class="form-group">
                                <label>Nhập email của bạn:</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Gửi mã OTP</button>
                        </form>
                    </div>
                    <div class="panel-footer text-center">
                        <a href="login.php">Quay lại đăng nhập</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>