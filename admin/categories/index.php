<?php
include '../../config/database.php';
/** @var mysqli $conn */

// Lấy danh sách danh mục
$sql = "SELECT * FROM danh_muc ORDER BY ma_danh_muc DESC";
$result = mysqli_query($conn, $sql);

include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<main class="main">
    <h1 class="box-title">Quản lý Danh mục</h1>
    
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
                <input type="text" placeholder="Tìm kiếm danh mục...">
            </div>
            <div class="inner-button-create">
                <a href="create.php" style="text-decoration: none;"><i class="fas fa-plus" style="margin-right: 8px;"></i> Thêm danh mục</a>
            </div>
        </div>
    </div>

    <div class="table-2">
        <table>
            <thead>
                <tr>
                    <th>Mã DM</th>
                    <th>Tên danh mục</th>
                    <th>Mô tả</th>
                    <th class="inner-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><span class="inner-code">#<?php echo $row['ma_danh_muc']; ?></span></td>
                            <td><?php echo htmlspecialchars($row['ten_danh_muc']); ?></td>
                            <td><span class="inner-text"><?php echo htmlspecialchars(substr($row['mo_ta'], 0, 50)); ?></span></td>
                            <td class="inner-center">
                                <div class="inner-buttons">
                                    <a href="edit.php?id=<?php echo $row['ma_danh_muc']; ?>" class="inner-edit"><i class="fas fa-edit"></i></a>
                                    <a href="delete.php?id=<?php echo $row['ma_danh_muc']; ?>" class="inner-delete" onclick="return confirm('Bạn có chắc chắn muốn xoá danh mục này?');"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="inner-center" style="padding: 30px;">Chưa có dữ liệu danh mục.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>
