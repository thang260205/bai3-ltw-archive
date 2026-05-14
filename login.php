<?php
session_start();
// Cờ báo hiệu đang ở thư mục gốc để nạp đúng file CSS
$is_root = true;

// Nếu đã đăng nhập thì chuyển hướng vào dashboard
if (isset($_SESSION['user_logged_in'])) {
    header("Location: admin/dashboard.php");
    exit();
}

$error = '';
if (isset($_POST['btn_login'])) {
    include 'config/database.php';
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = trim($_POST['password']);
    
    $sql = "SELECT * FROM users WHERE ten_dang_nhap = '$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if ($password === $user['mat_khau'] || password_verify($password, $user['mat_khau'])) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_name'] = !empty($user['ho_ten']) ? $user['ho_ten'] : $user['ten_dang_nhap'];
            header("Location: admin/dashboard.php");
            exit();
        } else {
            $error = "Mật khẩu không chính xác!";
        }
    } else {
        $error = "Tên đăng nhập không tồn tại!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập hệ thống</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #4880FF 0%, #8A2BE2 100%);
            font-family: "Nunito Sans", sans-serif;
        }
        .login-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        .login-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 420px;
            padding: 40px 35px;
            box-sizing: border-box;
        }
        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }
        .login-header .logo-icon {
            font-size: 36px;
            color: var(--color-primary);
            background: #e7edff;
            width: 80px;
            height: 80px;
            line-height: 80px;
            border-radius: 50%;
            display: inline-block;
            margin-bottom: 15px;
        }
        .login-header h2 {
            margin: 0;
            color: #202224;
            font-size: 26px;
            font-weight: 800;
        }
        .login-header p {
            color: #979797;
            font-size: 15px;
            margin: 8px 0 0 0;
        }
        .input-group {
            margin-bottom: 22px;
        }
        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            font-size: 14px;
            color: #404040;
        }
        .input-wrapper {
            position: relative;
        }
        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #A0A0A0;
            font-size: 16px;
            transition: color 0.3s ease;
        }
        .input-wrapper input {
            width: 100%;
            padding: 14px 16px 14px 45px;
            border: 1.5px solid #E2E2E2;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #FAFAFA;
            box-sizing: border-box;
        }
        .input-wrapper input:focus {
            border-color: var(--color-primary);
            background: #fff;
            outline: none;
            box-shadow: 0 0 0 4px rgba(72, 128, 255, 0.1);
        }
        .input-wrapper input:focus + i {
            color: var(--color-primary);
        }
        .login-btn {
            width: 100%;
            padding: 15px;
            background: var(--color-primary);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        .login-btn:hover {
            background: #396AFF;
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(72, 128, 255, 0.3);
        }
        .alert-error {
            background: #ffe5e5;
            color: #d63031;
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 25px;
            text-align: center;
            border: 1px solid #ffcccc;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <div class="logo-icon"><i class="fas fa-boxes"></i></div>
                <h2>Đăng Nhập</h2>
                <p>Hệ thống Quản lý Sản phẩm</p>
            </div>
            <?php if ($error != ''): ?>
                <div class="alert-error">
                    <i class="fas fa-exclamation-circle" style="margin-right: 5px;"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <div class="input-group">
                    <label>Tên đăng nhập</label>
                    <div class="input-wrapper">
                        <input type="text" name="username" placeholder="Nhập tên đăng nhập..." required>
                        <i class="fas fa-user"></i>
                    </div>
                </div>
                <div class="input-group">
                    <label>Mật khẩu</label>
                    <div class="input-wrapper">
                        <input type="password" name="password" placeholder="Nhập mật khẩu..." required>
                        <i class="fas fa-lock"></i>
                    </div>
                </div>
                <button type="submit" name="btn_login" class="login-btn">
                    Đăng Nhập <i class="fas fa-sign-in-alt" style="margin-left: 8px;"></i>
                </button>
            </form>
        </div>
    </div>
</body>
</html>