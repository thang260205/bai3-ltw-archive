<?php
/** @var string $base_url */
?>
<!-- Sidebar -->
<aside class="sider">
    <ul class="inner-menu">
        <li><a href="<?php echo $base_url; ?>admin/dashboard.php" class="<?php echo strpos($_SERVER['REQUEST_URI'], 'dashboard.php') !== false ? 'active' : ''; ?>"><i class="fas fa-home"></i> Tổng quan</a></li>
        <li><a href="<?php echo $base_url; ?>admin/categories/index.php" class="<?php echo strpos($_SERVER['REQUEST_URI'], 'categories') !== false ? 'active' : ''; ?>"><i class="fas fa-list"></i> Danh mục</a></li>
        <li><a href="<?php echo $base_url; ?>admin/products/index.php" class="<?php echo strpos($_SERVER['REQUEST_URI'], 'products') !== false ? 'active' : ''; ?>"><i class="fas fa-box"></i> Sản phẩm</a></li>
        <li><a href="<?php echo $base_url; ?>admin/suppliers/index.php" class="<?php echo strpos($_SERVER['REQUEST_URI'], 'suppliers') !== false ? 'active' : ''; ?>"><i class="fas fa-truck"></i> Nhà cung cấp</a></li>
    </ul>
</aside>

<div class="sider-overlay"></div>