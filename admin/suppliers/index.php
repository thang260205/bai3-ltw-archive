<?php
include '../../config/database.php';
/** @var mysqli $conn */

// Lấy danh sách nhà cung cấp
$sql = "SELECT * FROM nha_cung_cap ORDER BY ma_ncc DESC";
$result = mysqli_query($conn, $sql);

include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<main class="main">
    <h1 class="box-title">Quản lý Nhà cung cấp</h1>
    
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
                <input type="text" placeholder="Tìm kiếm nhà cung cấp...">
            </div>
            <div class="inner-button-create">
                <a href="create.php" style="text-decoration: none;"><i class="fas fa-plus" style="margin-right: 8px;"></i> Thêm nhà cung cấp</a>
            </div>
        </div>
    </div>

    <div class="table-2">
        <table>
            <thead>
                <tr>
                    <th>Mã NCC</th>
                    <th>Tên nhà cung cấp</th>
                    <th>Số điện thoại</th>
                    <th>Địa chỉ</th>
                    <th class="inner-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><span class="inner-code">#<?php echo $row['ma_ncc']; ?></span></td>
                            <td><?php echo htmlspecialchars($row['ten_ncc']); ?></td>
                            <td><?php echo htmlspecialchars($row['so_dien_thoai'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($row['dia_chi'] ?? '-'); ?></td>
                            <td class="inner-center">
                                <div class="inner-buttons">
                                    <a href="edit.php?id=<?php echo $row['ma_ncc']; ?>" class="inner-edit"><i class="fas fa-edit"></i></a>
                                    <a href="delete.php?id=<?php echo $row['ma_ncc']; ?>" class="inner-delete" onclick="return confirm('Bạn có chắc chắn muốn xoá nhà cung cấp này?');"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="inner-center" style="padding: 30px;">Chưa có dữ liệu nhà cung cấp.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>
