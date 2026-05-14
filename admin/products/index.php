<?php
include '../../config/database.php';
/** @var mysqli $conn */

// Lấy danh sách danh mục và nhà cung cấp cho dropdown filter
$res_categories = mysqli_query($conn, "SELECT ma_danh_muc, ten_danh_muc FROM danh_muc ORDER BY ten_danh_muc");
$res_suppliers = mysqli_query($conn, "SELECT ma_ncc, ten_ncc FROM nha_cung_cap ORDER BY ten_ncc");

// Tiếp nhận các tham số lọc từ URL (GET)
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category_id = isset($_GET['category']) ? intval($_GET['category']) : 0;
$supplier_id = isset($_GET['supplier']) ? intval($_GET['supplier']) : 0;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Xây dựng mảng điều kiện WHERE
$where_clauses = [];
if ($search !== '') {
    $safe_search = mysqli_real_escape_string($conn, $search);
    $where_clauses[] = "(sp.ten_san_pham LIKE '%$safe_search%' OR sp.ma_san_pham LIKE '%$safe_search%')";
}
if ($category_id > 0) {
    $where_clauses[] = "sp.ma_danh_muc = $category_id";
}
if ($supplier_id > 0) {
    $where_clauses[] = "sp.ma_ncc = $supplier_id";
}

$where_sql = count($where_clauses) > 0 ? "WHERE " . implode(" AND ", $where_clauses) : "";

// Xây dựng chuỗi ORDER BY
$order_sql = "ORDER BY sp.ma_san_pham DESC";
if ($sort === 'price_asc') $order_sql = "ORDER BY sp.gia ASC";
elseif ($sort === 'price_desc') $order_sql = "ORDER BY sp.gia DESC";
elseif ($sort === 'stock_asc') $order_sql = "ORDER BY sp.ton_kho ASC";
elseif ($sort === 'stock_desc') $order_sql = "ORDER BY sp.ton_kho DESC";
elseif ($sort === 'best_seller') $order_sql = "ORDER BY sp.so_luong_ban DESC";

// Truy vấn danh sách sản phẩm (có áp dụng Filter & Sort)
$sql = "
    SELECT sp.*, dm.ten_danh_muc, ncc.ten_ncc, dv.ten_don_vi 
    FROM san_pham sp 
    LEFT JOIN danh_muc dm ON sp.ma_danh_muc = dm.ma_danh_muc
    LEFT JOIN nha_cung_cap ncc ON sp.ma_ncc = ncc.ma_ncc
    LEFT JOIN don_vi dv ON sp.ma_don_vi = dv.ma_don_vi
    $where_sql
    $order_sql
";

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

    <form action="index.php" method="GET">
        <!-- Bộ lọc chi tiết -->
        <div class="section-4">
            <div class="inner-wrap">
                <div class="inner-item">
                    <i class="fas fa-list" style="color: #979797;"></i>
                    <select name="category" onchange="this.form.submit()">
                        <option value="0">Tất cả Danh mục</option>
                        <?php if ($res_categories): while ($cat = mysqli_fetch_assoc($res_categories)): ?>
                            <option value="<?php echo $cat['ma_danh_muc']; ?>" <?php echo $category_id == $cat['ma_danh_muc'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['ten_danh_muc']); ?>
                            </option>
                        <?php endwhile; endif; ?>
                    </select>
                </div>
                <div class="inner-item">
                    <i class="fas fa-truck" style="color: #979797;"></i>
                    <select name="supplier" onchange="this.form.submit()">
                        <option value="0">Tất cả Nhà cung cấp</option>
                        <?php if ($res_suppliers): while ($sup = mysqli_fetch_assoc($res_suppliers)): ?>
                            <option value="<?php echo $sup['ma_ncc']; ?>" <?php echo $supplier_id == $sup['ma_ncc'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($sup['ten_ncc']); ?>
                            </option>
                        <?php endwhile; endif; ?>
                    </select>
                </div>
                <div class="inner-item">
                    <i class="fas fa-sort" style="color: #979797;"></i>
                    <select name="sort" onchange="this.form.submit()">
                        <option value="newest" <?php echo $sort == 'newest' ? 'selected' : ''; ?>>Mới nhất</option>
                        <option value="price_asc" <?php echo $sort == 'price_asc' ? 'selected' : ''; ?>>Giá tăng dần</option>
                        <option value="price_desc" <?php echo $sort == 'price_desc' ? 'selected' : ''; ?>>Giá giảm dần</option>
                        <option value="best_seller" <?php echo $sort == 'best_seller' ? 'selected' : ''; ?>>Bán chạy nhất</option>
                        <option value="stock_desc" <?php echo $sort == 'stock_desc' ? 'selected' : ''; ?>>Tồn kho cao nhất</option>
                        <option value="stock_asc" <?php echo $sort == 'stock_asc' ? 'selected' : ''; ?>>Sắp hết hàng</option>
                    </select>
                </div>
                <?php if ($search !== '' || $category_id > 0 || $supplier_id > 0 || $sort !== 'newest'): ?>
                <div class="inner-item">
                    <a href="index.php" class="inner-reset"><i class="fas fa-times" style="margin-right: 5px;"></i> Xóa bộ lọc</a>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="section-5">
            <div class="inner-wrap" style="justify-content: space-between; width: 100%;">
                <div class="inner-search">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Tìm kiếm tên, mã SP (Nhấn Enter)...">
                </div>
                <div class="inner-button-create">
                    <a href="create.php" style="text-decoration: none;"><i class="fas fa-plus" style="margin-right: 8px;"></i> Thêm sản phẩm</a>
                </div>
            </div>
        </div>
    </form>

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