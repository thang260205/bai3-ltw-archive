<?php
include '../../config/database.php';
/** @var mysqli $conn */

// Kiểm tra có id không
if (empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);

// Lấy thông tin sản phẩm
$sql = "SELECT * FROM san_pham WHERE ma_san_pham = $id";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit();
}

$product = mysqli_fetch_assoc($result);

// Lấy danh sách danh mục, nhà cung cấp, đơn vị tính
$sql_categories = "SELECT * FROM danh_muc ORDER BY ten_danh_muc";
$result_categories = mysqli_query($conn, $sql_categories);

$sql_suppliers = "SELECT * FROM nha_cung_cap ORDER BY ten_ncc";
$result_suppliers = mysqli_query($conn, $sql_suppliers);

$sql_units = "SELECT * FROM don_vi ORDER BY ten_don_vi";
$result_units = mysqli_query($conn, $sql_units);

$error = '';
$success = '';

if (isset($_POST['btn_save'])) {
    $ten_san_pham = mysqli_real_escape_string($conn, trim($_POST['ten_san_pham']));
    $gia = mysqli_real_escape_string($conn, trim($_POST['gia']));
    $ton_kho = mysqli_real_escape_string($conn, trim($_POST['ton_kho']));
    $mo_ta = mysqli_real_escape_string($conn, trim($_POST['mo_ta']));
    $ma_danh_muc = !empty($_POST['ma_danh_muc']) ? intval($_POST['ma_danh_muc']) : 'NULL';
    $ma_ncc = !empty($_POST['ma_ncc']) ? intval($_POST['ma_ncc']) : 'NULL';
    $ma_don_vi = !empty($_POST['ma_don_vi']) ? intval($_POST['ma_don_vi']) : 'NULL';
    
    $hinh_anh = $product['hinh_anh'];
    
    // Xử lý upload ảnh
    if (!empty($_FILES['hinh_anh']['name'])) {
        $file_name = $_FILES['hinh_anh']['name'];
        $file_tmp = $_FILES['hinh_anh']['tmp_name'];
        $file_size = $_FILES['hinh_anh']['size'];
        
        // Kiểm tra kích thước file (max 5MB)
        if ($file_size > 5242880) {
            $error = "Kích thước file quá lớn (tối đa 5MB)";
        } else {
            $allowed = array('jpg', 'jpeg', 'png', 'gif');
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            if (!in_array($file_ext, $allowed)) {
                $error = "Định dạng file không được hỗ trợ";
            } else {
                require_once '../../models/Database.php';
                $cloudinary_url = uploadToCloudinary($file_tmp);
                
                if ($cloudinary_url) {
                    // Xóa ảnh cũ (chỉ nếu là ảnh lưu ở localhost)
                    if (!empty($product['hinh_anh']) && strpos($product['hinh_anh'], 'http') === false) {
                        @unlink('../../assets/uploads/' . $product['hinh_anh']);
                    }
                    $hinh_anh = $cloudinary_url;
                } else {
                    $error = "Lỗi khi tải ảnh lên Cloudinary";
                }
            }
        }
    }
    
    // Kiểm tra dữ liệu bắt buộc
    if (empty($ten_san_pham)) {
        $error = "Vui lòng nhập tên sản phẩm";
    } elseif (empty($gia) || $gia <= 0) {
        $error = "Vui lòng nhập giá hợp lệ";
    } elseif (empty($ton_kho) || $ton_kho < 0) {
        $error = "Vui lòng nhập tồn kho hợp lệ";
    }
    
    if (empty($error)) {
        $sql = "UPDATE san_pham SET 
                ten_san_pham = '$ten_san_pham', 
                gia = '$gia', 
                ton_kho = '$ton_kho', 
                mo_ta = '$mo_ta', 
                hinh_anh = '$hinh_anh',
                ma_danh_muc = $ma_danh_muc, 
                ma_ncc = $ma_ncc, 
                ma_don_vi = $ma_don_vi 
                WHERE ma_san_pham = $id";
        
        if (mysqli_query($conn, $sql)) {
            $success = "Cập nhật sản phẩm thành công!";
            // Cập nhật lịch sử giá nếu giá thay đổi
            if ($product['gia'] != $gia) {
                $sql_price_history = "INSERT INTO lich_su_gia (ma_san_pham, gia_cu, gia_moi) VALUES ($id, {$product['gia']}, '$gia')";
                mysqli_query($conn, $sql_price_history);
            }
            header("Refresh: 1; url=index.php");
        } else {
            $error = "Lỗi: " . mysqli_error($conn);
        }
    }
}

include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<main class="main">
    <h1 class="box-title">Chỉnh sửa Sản phẩm</h1>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <div class="form-container">
        <form method="POST" enctype="multipart/form-data" class="form-group">
            <div class="form-row">
                <div class="form-col">
                    <label for="ten_san_pham">Tên sản phẩm *</label>
                    <input type="text" id="ten_san_pham" name="ten_san_pham" required value="<?php echo htmlspecialchars($product['ten_san_pham']); ?>">
                </div>
                <div class="form-col">
                    <label for="gia">Giá bán *</label>
                    <input type="number" id="gia" name="gia" required min="1" value="<?php echo htmlspecialchars($product['gia']); ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-col">
                    <label for="ton_kho">Tồn kho *</label>
                    <input type="number" id="ton_kho" name="ton_kho" required min="0" value="<?php echo htmlspecialchars($product['ton_kho']); ?>">
                </div>
                <div class="form-col">
                    <label for="ma_danh_muc">Danh mục</label>
                    <select id="ma_danh_muc" name="ma_danh_muc">
                        <option value="">-- Chọn danh mục --</option>
                        <?php 
                        mysqli_data_seek($result_categories, 0);
                        while ($row = mysqli_fetch_assoc($result_categories)): ?>
                            <option value="<?php echo $row['ma_danh_muc']; ?>" <?php echo $product['ma_danh_muc'] == $row['ma_danh_muc'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($row['ten_danh_muc']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-col">
                    <label for="ma_ncc">Nhà cung cấp</label>
                    <select id="ma_ncc" name="ma_ncc">
                        <option value="">-- Chọn nhà cung cấp --</option>
                        <?php 
                        mysqli_data_seek($result_suppliers, 0);
                        while ($row = mysqli_fetch_assoc($result_suppliers)): ?>
                            <option value="<?php echo $row['ma_ncc']; ?>" <?php echo $product['ma_ncc'] == $row['ma_ncc'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($row['ten_ncc']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-col">
                    <label for="ma_don_vi">Đơn vị tính</label>
                    <select id="ma_don_vi" name="ma_don_vi">
                        <option value="">-- Chọn đơn vị --</option>
                        <?php 
                        mysqli_data_seek($result_units, 0);
                        while ($row = mysqli_fetch_assoc($result_units)): ?>
                            <option value="<?php echo $row['ma_don_vi']; ?>" <?php echo $product['ma_don_vi'] == $row['ma_don_vi'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($row['ten_don_vi']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-col full">
                    <label for="mo_ta">Mô tả</label>
                    <textarea id="mo_ta" name="mo_ta" rows="4"><?php echo htmlspecialchars($product['mo_ta']); ?></textarea>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-col full">
                    <label for="hinh_anh">Hình ảnh sản phẩm</label>
                    <?php if (!empty($product['hinh_anh'])): ?>
                        <div style="margin-bottom: 10px;">
                            <?php 
                            $img_src = strpos($product['hinh_anh'], 'http') === 0 ? $product['hinh_anh'] : '../../assets/uploads/' . $product['hinh_anh'];
                            ?>
                            <img src="<?php echo htmlspecialchars($img_src); ?>" style="max-width: 200px; max-height: 200px; object-fit: contain;">
                        </div>
                    <?php endif; ?>
                    <input type="file" id="hinh_anh" name="hinh_anh" accept="image/*">
                    <small>Tối đa 5MB. Định dạng: JPG, PNG, GIF</small>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" name="btn_save" class="btn btn-primary">Lưu thay đổi</button>
                <a href="index.php" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>
