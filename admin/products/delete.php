<?php
include '../../config/database.php';
/** @var mysqli $conn */

// Kiểm tra có id không
if (empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);

// Lấy thông tin sản phẩm để xóa file ảnh
$sql = "SELECT hinh_anh FROM san_pham WHERE ma_san_pham = $id";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $product = mysqli_fetch_assoc($result);
    
    // Xóa file ảnh nếu tồn tại (chỉ xử lý file ở localhost)
    if (!empty($product['hinh_anh']) && strpos($product['hinh_anh'], 'http') === false) {
        $image_path = '../../assets/uploads/' . $product['hinh_anh'];
        if (file_exists($image_path)) {
            @unlink($image_path);
        }
    }
    
    // Xóa bản ghi từ bảng lich_su_gia (khóa ngoại)
    $sql_delete_history = "DELETE FROM lich_su_gia WHERE ma_san_pham = $id";
    mysqli_query($conn, $sql_delete_history);
    
    // Xóa sản phẩm
    $sql_delete = "DELETE FROM san_pham WHERE ma_san_pham = $id";
    
    if (mysqli_query($conn, $sql_delete)) {
        header("Location: index.php?message=Xóa sản phẩm thành công!");
    } else {
        header("Location: index.php?error=Lỗi khi xóa sản phẩm!");
    }
} else {
    header("Location: index.php?error=Sản phẩm không tồn tại!");
}
exit();
?>
