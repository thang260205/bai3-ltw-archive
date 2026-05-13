<?php
include '../../config/database.php';
/** @var mysqli $conn */

// Kiểm tra có id không
if (empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);

// Xóa nhà cung cấp
$sql_delete = "DELETE FROM nha_cung_cap WHERE ma_ncc = $id";

if (mysqli_query($conn, $sql_delete)) {
    header("Location: index.php?message=Xóa nhà cung cấp thành công!");
} else {
    header("Location: index.php?error=Lỗi khi xóa nhà cung cấp!");
}
exit();
?>
