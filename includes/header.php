<?php 
// Dùng đường dẫn tuyệt đối để CSS/JS và Links luôn hoạt động đúng dù ở bất kỳ thư mục con nào
$base_url = "/bai3php/"; 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống Quản lý Sản phẩm</title>
    
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Custom Style CSS -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/style.css">
    
    <!-- Main JS -->
    <script src="<?php echo $base_url; ?>assets/js/main.js"></script>
</head>
<body>

<!-- Header -->
<header class="header">
    <div class="inner-logo">
        <a href="<?php echo $base_url; ?>admin/dashboard.php">
            <span>Admin</span><span>Panel</span>
        </a>
    </div>
    <button class="inner-button-menu"><i class="fas fa-bars"></i></button>
    <div class="inner-wrap">
        <div class="inner-account">
            <div class="inner-avatar">
                <img src="https://ui-avatars.com/api/?name=Admin&background=4880FF&color=fff" alt="Avatar">
            </div>
            <div class="inner-text">
                <div class="inner-name">Administrator</div>
                <div class="inner-role">Quản trị viên</div>
            </div>
        </div>
    </div>
</header>