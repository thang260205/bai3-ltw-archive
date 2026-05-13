<?php
include '../config/database.php';
/** @var mysqli $conn */

// Thống kê tổng sản phẩm
$sql_total_products = "SELECT COUNT(*) as total FROM san_pham";
$total_products = mysqli_fetch_assoc(mysqli_query($conn, $sql_total_products))['total'] ?? 0;

// Thống kê tổng danh mục
$sql_total_categories = "SELECT COUNT(*) as total FROM danh_muc";
$total_categories = mysqli_fetch_assoc(mysqli_query($conn, $sql_total_categories))['total'] ?? 0;

// Thống kê sản phẩm sắp hết hàng (tồn kho < 10)
$sql_low_stock = "SELECT COUNT(*) as total FROM san_pham WHERE ton_kho < 10";
$low_stock = mysqli_fetch_assoc(mysqli_query($conn, $sql_low_stock))['total'] ?? 0;

// Top 10 Sản Phẩm Bán Chạy
$sql_top_products = "SELECT sp.*, dm.ten_danh_muc FROM san_pham sp LEFT JOIN danh_muc dm ON sp.ma_danh_muc = dm.ma_danh_muc ORDER BY sp.so_luong_ban DESC LIMIT 10";
$res_top_products = mysqli_query($conn, $sql_top_products);

// Lấy dữ liệu lịch sử giá cho biểu đồ
$sql_history = "SELECT lsg.ma_san_pham, sp.ten_san_pham, lsg.gia_cu, lsg.gia_moi, DATE_FORMAT(lsg.ngay_thay_doi, '%d/%m/%Y %H:%i') as ngay
                FROM lich_su_gia lsg
                JOIN san_pham sp ON lsg.ma_san_pham = sp.ma_san_pham
                ORDER BY lsg.ngay_thay_doi ASC";
$res_history = mysqli_query($conn, $sql_history);

$history_data = [];
$products_with_history = [];

if ($res_history) {
    while ($row = mysqli_fetch_assoc($res_history)) {
        $id = $row['ma_san_pham'];
        if (!isset($history_data[$id])) {
            $history_data[$id] = ['name' => $row['ten_san_pham'], 'labels' => ['Trước khi đổi'], 'prices' => [$row['gia_cu']]];
            $products_with_history[$id] = $row['ten_san_pham'];
        }
        $history_data[$id]['labels'][] = $row['ngay'];
        $history_data[$id]['prices'][] = $row['gia_moi'];
    }
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<main class="main">
    <h1 class="box-title">Tổng quan hệ thống</h1>

    <!-- Thống kê tổng quan -->
    <div class="section-1">
        <div class="inner-item">
            <div class="inner-icon">
                <i class="fas fa-boxes" style="font-size: 40px; color: var(--color-primary);"></i>
            </div>
            <div class="inner-content">
                <div class="inner-title">Tổng sản phẩm</div>
                <div class="inner-number"><?php echo number_format($total_products); ?></div>
            </div>
        </div>
        <div class="inner-item">
            <div class="inner-icon">
                <i class="fas fa-list" style="font-size: 40px; color: #00B69B;"></i>
            </div>
            <div class="inner-content">
                <div class="inner-title">Tổng danh mục</div>
                <div class="inner-number"><?php echo number_format($total_categories); ?></div>
            </div>
        </div>
        <div class="inner-item">
            <div class="inner-icon">
                <i class="fas fa-exclamation-triangle" style="font-size: 40px; color: #EF3826;"></i>
            </div>
            <div class="inner-content">
                <div class="inner-title">Sắp hết hàng</div>
                <div class="inner-number"><?php echo number_format($low_stock); ?></div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ lịch sử giá -->
    <div class="section-2">
        <div class="inner-head">
            <h2 class="inner-title">Biểu đồ Lịch sử giá</h2>
            <div class="inner-filter">
                <select id="productSelect" style="height: 35px; border: 1px solid #D5D5D5; border-radius: 4px; padding: 0 10px; font-weight: 600; outline: none; cursor: pointer; max-width: 250px;">
                    <?php if (empty($products_with_history)): ?>
                        <option value="">Chưa có dữ liệu</option>
                    <?php else: ?>
                        <option value="">-- Chọn sản phẩm --</option>
                        <?php foreach ($products_with_history as $id => $name): ?>
                            <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($name); ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
        <div class="inner-chart">
            <?php if (empty($products_with_history)): ?>
                <div style="height: 100%; display: flex; align-items: center; justify-content: center; color: #999; font-weight: 600;">
                    Hãy thay đổi giá sản phẩm để xem biểu đồ biến động
                </div>
            <?php else: ?>
                <canvas id="priceChart"></canvas>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bảng sản phẩm bán chạy -->
    <div class="section-3">
        <h2 class="inner-title">Top 10 Sản Phẩm Bán Chạy</h2>
        <div class="table-1">
            <table>
                <thead>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá bán</th>
                        <th>Đã bán</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($res_top_products && mysqli_num_rows($res_top_products) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($res_top_products)): ?>
                            <tr>
                                <td>
                                    <div class="inner-item">
                                        <div class="inner-image">
                                            <?php 
                                            $img_src = 'https://via.placeholder.com/76x76/4880FF/FFFFFF?text=SP';
                                            if (!empty($row['hinh_anh'])) {
                                                $img_src = strpos($row['hinh_anh'], 'http') === 0 ? $row['hinh_anh'] : '../assets/uploads/' . $row['hinh_anh'];
                                            }
                                            ?>
                                            <img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($row['ten_san_pham']); ?>">
                                        </div>
                                        <div class="inner-content">
                                            <div class="inner-name"><?php echo htmlspecialchars($row['ten_san_pham']); ?></div>
                                            <div class="inner-quantity">Tồn kho: <?php echo $row['ton_kho']; ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="inner-text"><?php echo htmlspecialchars($row['ten_danh_muc'] ?? 'Không có'); ?></span></td>
                                <td><span class="inner-text"><?php echo number_format($row['gia'], 0, ',', '.'); ?> đ</span></td>
                                <td><span class="badge badge-green"><?php echo $row['so_luong_ban']; ?></span></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="text-align: center; padding: 20px;">Chưa có dữ liệu sản phẩm bán chạy.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php if (!empty($products_with_history)): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const historyData = <?php echo json_encode($history_data); ?>;
    const ctx = document.getElementById('priceChart').getContext('2d');
    let priceChart;

    function renderChart(productId) {
        if (priceChart) {
            priceChart.destroy();
        }

        let labels = [];
        let data = [];
        let labelName = 'Giá bán';

        if (productId && historyData[productId]) {
            labels = historyData[productId].labels;
            data = historyData[productId].prices;
            labelName = 'Giá bán của: ' + historyData[productId].name;
        } else if (Object.keys(historyData).length > 0) {
            // Tự động load sản phẩm đầu tiên
            const firstId = Object.keys(historyData)[0];
            labels = historyData[firstId].labels;
            data = historyData[firstId].prices;
            labelName = 'Giá bán của: ' + historyData[firstId].name;
            document.getElementById('productSelect').value = firstId;
        }

        priceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: labelName,
                    data: data,
                    borderColor: '#4880FF',
                    backgroundColor: 'rgba(72, 128, 255, 0.2)',
                    borderWidth: 2,
                    pointBackgroundColor: '#4880FF',
                    pointRadius: 5,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        ticks: {
                            callback: function(value) { return value.toLocaleString('vi-VN') + ' đ'; }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) { return context.raw.toLocaleString('vi-VN') + ' đ'; }
                        }
                    }
                }
            }
        });
    }

    // Khởi tạo biểu đồ
    renderChart();

    // Lắng nghe sự kiện thay đổi sản phẩm trên dropdown
    document.getElementById('productSelect').addEventListener('change', function() {
        renderChart(this.value);
    });
</script>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>