<?php
include '../../config/database.php';
/** @var mysqli $conn */

// Kiểm tra có id không
if (empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);

// Lấy thông tin danh mục
$sql = "SELECT * FROM danh_muc WHERE ma_danh_muc = $id";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit();
}

$category = mysqli_fetch_assoc($result);

$error = '';
$success = '';

if (isset($_POST['btn_save'])) {
    $ten_danh_muc = mysqli_real_escape_string($conn, trim($_POST['ten_danh_muc']));
    $mo_ta = mysqli_real_escape_string($conn, trim($_POST['mo_ta']));
    
    // Kiểm tra dữ liệu bắt buộc
    if (empty($ten_danh_muc)) {
        $error = "Vui lòng nhập tên danh mục";
    } else {
        $sql = "UPDATE danh_muc SET ten_danh_muc = '$ten_danh_muc', mo_ta = '$mo_ta' WHERE ma_danh_muc = $id";
        
        if (mysqli_query($conn, $sql)) {
            $success = "Cập nhật danh mục thành công!";
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
    <h1 class="box-title">Chỉnh sửa Danh mục</h1>
    
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
                    <label for="ten_danh_muc">Tên danh mục *</label>
                    <input type="text" id="ten_danh_muc" name="ten_danh_muc" required value="<?php echo htmlspecialchars($category['ten_danh_muc']); ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-col full">
                    <label for="mo_ta">Mô tả</label>
                    <textarea id="mo_ta" name="mo_ta" rows="4"><?php echo htmlspecialchars($category['mo_ta']); ?></textarea>
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
