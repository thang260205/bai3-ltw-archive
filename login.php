<?php
session_start();
// Cờ báo hiệu đang ở thư mục gốc để nạp đúng file CSS
$is_root = true;

// Nếu đã đăng nhập thì chuyển hướng vào dashboard
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: admin/dashboard.php");
    exit();
}

$error = '';
if (isset($_POST['btn_login'])) {
    include 'config/database.php';
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = trim($_POST['password']);
    
    // Lưu ý: Cần thêm bảng `admin` (gồm các cột: username, password) vào cơ sở dữ liệu qlsanpham
    $sql = "SELECT * FROM admin WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $admin = mysqli_fetch_assoc($result);
        if ($password === $admin['password'] || password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: admin/dashboard.php");
            exit();
        } else {
            $error = "Mật khẩu không chính xác!";
        }
    } else {
        $error = "Tên đăng nhập không tồn tại! (Vui lòng tạo bảng admin)";
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
        .login-wrapper {
            display: flex; align-items: center; justify-content: center; min-height: 100vh; background-color: #F5F6FA;
        }
        .login-box {
            width: 450px; max-width: 90%;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="section-8 login-box">
            <h2 class="inner-section-title" style="text-align: center; border-bottom: none; font-size: 24px; color: var(--color-primary);"><i class="fas fa-shield-alt"></i> ĐĂNG NHẬP</h2>
            <?php if ($error != ''): ?>
                <div style="color: #EF3826; text-align: center; margin-bottom: 15px; font-weight: 600;"><?php echo $error; ?></div>
            <?php endif; ?>
            <form action="login.php" method="POST" style="grid-template-columns: 1fr;">
                <div class="inner-group">
                    <label class="inner-label">Tên đăng nhập</label>
                    <input type="text" name="username" placeholder="Nhập tên đăng nhập..." required>
                </div>
                <div class="inner-group">
                    <label class="inner-label">Mật khẩu</label>
                    <input type="password" name="password" placeholder="Nhập mật khẩu..." required>
                </div>
                <div class="inner-button">
                    <button type="submit" name="btn_login" style="width: 100%;">Đăng Nhập</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>