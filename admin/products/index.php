<?php
include '../../config/database.php';
/** @var mysqli $conn */

// Lấy danh sách sản phẩm kèm theo tên danh mục, nhà cung cấp và đơn vị tính
$sql = "SELECT sp.*, dm.ten_danh_muc, ncc.ten_ncc, dv.ten_don_vi 
        FROM san_pham sp 
        LEFT JOIN danh_muc dm ON sp.ma_danh_muc = dm.ma_danh_muc
        LEFT JOIN nha_cung_cap ncc ON sp.ma_ncc = ncc.ma_ncc
        LEFT JOIN don_vi dv ON sp.ma_don_vi = dv.ma_don_vi
        ORDER BY sp.ma_san_pham DESC";
$result = mysqli_query($conn, $sql);

include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<main class="main">
    <h1 class="box-title">Quản lý Sản phẩm</h1>
    
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_GET['message']); ?></div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <div class="section-5">
        <div class="inner-wrap" style="justify-content: space-between; width: 100%;">
            <div class="inner-search">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Tìm kiếm sản phẩm...">
            </div>
            <div class="inner-button-create">
                <a href="create.php" style="text-decoration: none;"><i class="fas fa-plus" style="margin-right: 8px;"></i> Thêm sản phẩm</a>
            </div>
        </div>
    </div>

    <div class="table-2">
        <table>
            <thead>
                <tr>
                    <th>Mã SP</th>
                    <th>Sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Nhà cung cấp</th>
                    <th>Giá bán</th>
                    <th>Tồn kho</th>
                    <th class="inner-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><span class="inner-code">#<?php echo $row['ma_san_pham']; ?></span></td>
                            <td>
                                <div class="inner-item">
                                    <div class="inner-image">
                                        <?php 
                                        $img_src = 'https://via.placeholder.com/76x76/4880FF/FFFFFF?text=SP';
                                        if (!empty($row['hinh_anh'])) {
                                            $img_src = strpos($row['hinh_anh'], 'http') === 0 ? $row['hinh_anh'] : '../../assets/uploads/' . $row['hinh_anh'];
                                        }
                                        ?>
                                        <img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($row['ten_san_pham']); ?>">
                                    </div>
                                    <div class="inner-content">
                                        <div class="inner-name"><?php echo htmlspecialchars($row['ten_san_pham']); ?></div>
                                        <div class="inner-quantity">Đã bán: <?php echo $row['so_luong_ban']; ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($row['ten_danh_muc'] ?? 'Chưa phân loại'); ?></td>
                            <td><?php echo htmlspecialchars($row['ten_ncc'] ?? 'Chưa rõ'); ?></td>
                            <td><span class="inner-text"><?php echo number_format($row['gia'], 0, ',', '.'); ?> đ / <?php echo $row['ten_don_vi'] ?? 'Cái'; ?></span></td>
                            <td><?php echo $row['ton_kho']; ?></td>
                            <td class="inner-center">
                                <div class="inner-buttons">
                                    <a href="edit.php?id=<?php echo $row['ma_san_pham']; ?>" class="inner-edit"><i class="fas fa-edit"></i></a>
                                    <a href="delete.php?id=<?php echo $row['ma_san_pham']; ?>" class="inner-delete" onclick="return confirm('Bạn có chắc chắn muốn xoá sản phẩm này?');"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="inner-center" style="padding: 30px;">Chưa có dữ liệu sản phẩm.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>