<?php
include '../../config/database.php';
/** @var mysqli $conn */

// Kiểm tra có id không
if (empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);

// Xóa danh mục
$sql_delete = "DELETE FROM danh_muc WHERE ma_danh_muc = $id";

if (mysqli_query($conn, $sql_delete)) {
    header("Location: index.php?message=Xóa danh mục thành công!");
} else {
    header("Location: index.php?error=Lỗi khi xóa danh mục!");
}
exit();
?>
