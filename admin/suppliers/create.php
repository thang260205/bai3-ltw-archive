<?php
include '../../config/database.php';
/** @var mysqli $conn */

$error = '';
$success = '';

if (isset($_POST['btn_save'])) {
    $ten_ncc = mysqli_real_escape_string($conn, trim($_POST['ten_ncc']));
    $so_dien_thoai = mysqli_real_escape_string($conn, trim($_POST['so_dien_thoai']));
    $dia_chi = mysqli_real_escape_string($conn, trim($_POST['dia_chi']));
    
    // Kiểm tra dữ liệu bắt buộc
    if (empty($ten_ncc)) {
        $error = "Vui lòng nhập tên nhà cung cấp";
    } else {
        $sql = "INSERT INTO nha_cung_cap (ten_ncc, so_dien_thoai, dia_chi) VALUES ('$ten_ncc', '$so_dien_thoai', '$dia_chi')";
        
        if (mysqli_query($conn, $sql)) {
            $success = "Thêm nhà cung cấp thành công!";
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
    <h1 class="box-title">Thêm Nhà cung cấp</h1>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <div class="form-container">
        <form method="POST" class="form-group">
            <div class="form-row">
                <div class="form-col full">
                    <label for="ten_ncc">Tên nhà cung cấp *</label>
                    <input type="text" id="ten_ncc" name="ten_ncc" required value="<?php echo isset($_POST['ten_ncc']) ? htmlspecialchars($_POST['ten_ncc']) : ''; ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-col">
                    <label for="so_dien_thoai">Số điện thoại</label>
                    <input type="text" id="so_dien_thoai" name="so_dien_thoai" value="<?php echo isset($_POST['so_dien_thoai']) ? htmlspecialchars($_POST['so_dien_thoai']) : ''; ?>">
                </div>
                <div class="form-col">
                    <label for="dia_chi">Địa chỉ</label>
                    <input type="text" id="dia_chi" name="dia_chi" value="<?php echo isset($_POST['dia_chi']) ? htmlspecialchars($_POST['dia_chi']) : ''; ?>">
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" name="btn_save" class="btn btn-primary">Lưu nhà cung cấp</button>
                <a href="index.php" class="btn btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>
