<?php
session_start();
// Cảnh báo chặn việc vô tình chạy file làm mất dữ liệu
if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes') {
    echo "<div style='font-family: Arial, sans-serif; text-align: center; margin-top: 50px;'>";
    echo "<h1 style='color: red;'>CẢNH BÁO NGUY HIỂM!</h1>";
    echo "<p style='font-size: 18px;'>File này sẽ <b>XÓA SẠCH TOÀN BỘ DỮ LIỆU HIỆN TẠI</b> của hệ thống để tạo lại dữ liệu mẫu (dummy data).</p>";
    echo "<br><a href='../admin/dashboard.php' style='background: #4880FF; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; margin-right: 15px;'>QUAY LẠI AN TOÀN</a>";
    echo "<a href='seeder.php?confirm=yes' style='background: #F93C65; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold;' onclick=\"return confirm('Bạn có CHẮC CHẮN 100% muốn XÓA TẤT CẢ dữ liệu hiện tại không?');\">Vẫn tiếp tục xóa dữ liệu</a>";
    echo "</div>";
    exit();
}

include '../config/database.php';
/** @var mysqli $conn */

/**
 * @param mysqli $conn
 */
function run_query(mysqli $conn, string $sql, string $message): void {
    if (!mysqli_query($conn, $sql)) {
        die("Lỗi khi thực thi '$message': " . mysqli_error($conn));
    }
}

echo "Bắt đầu quá trình tạo dữ liệu mẫu...<br>";

// --- 1. Xóa dữ liệu cũ ---
run_query($conn, "SET NAMES 'utf8mb4'", "SET NAMES");
run_query($conn, "SET FOREIGN_KEY_CHECKS = 0", "Tắt kiểm tra khóa ngoại");
run_query($conn, "TRUNCATE TABLE lich_su_gia", "Xóa bảng lich_su_gia");
run_query($conn, "TRUNCATE TABLE san_pham", "Xóa bảng san_pham");
run_query($conn, "TRUNCATE TABLE users", "Xóa bảng users");
run_query($conn, "TRUNCATE TABLE danh_muc", "Xóa bảng danh_muc");
run_query($conn, "TRUNCATE TABLE nha_cung_cap", "Xóa bảng nha_cung_cap");
run_query($conn, "TRUNCATE TABLE don_vi", "Xóa bảng don_vi");
run_query($conn, "SET FOREIGN_KEY_CHECKS = 1", "Bật kiểm tra khóa ngoại");
echo "Đã xóa dữ liệu cũ thành công.<br>";

// --- 2. Thêm dữ liệu cơ bản ---
run_query($conn, "INSERT INTO don_vi (ten_don_vi) VALUES ('Cái'), ('Chiếc'), ('Kg'), ('Gói'), ('Hộp')", "Thêm đơn vị");
run_query($conn, "INSERT INTO nha_cung_cap (ten_ncc, so_dien_thoai, dia_chi) VALUES 
    ('Công ty Điện Máy X', '0901111111', 'Hà Nội'), 
    ('Xưởng May Y', '0902222222', 'TP.HCM'),
    ('Nông Sản Z', '0903333333', 'Đà Lạt')", "Thêm nhà cung cấp");
run_query($conn, "INSERT INTO danh_muc (ten_danh_muc, mo_ta) VALUES 
    ('Đồ điện tử', 'Thiết bị công nghệ'), 
    ('Thời trang', 'Quần áo phụ kiện'), 
    ('Đồ ăn', 'Thực phẩm các loại')", "Thêm danh mục");
echo "Đã thêm dữ liệu cơ bản (Danh mục, NCC, Đơn vị).<br>";

// --- 2.5. Thêm tài khoản Admin ---
$password_hashed = password_hash('123456', PASSWORD_DEFAULT);
run_query($conn, "INSERT INTO users (ten_dang_nhap, mat_khau, ho_ten) VALUES ('admin', '$password_hashed', 'Quản Trị Viên')", "Thêm tài khoản admin");

// --- 3. Thêm sản phẩm và lịch sử giá (Sử dụng Prepared Statements) ---
// Danh sách mẫu sản phẩm thực tế
$realistic_products = [
    1 => [ // Đồ điện tử
        ['name' => 'Điện thoại iPhone 15 Pro Max 256GB', 'price' => 29990000, 'img_kw' => 'iphone'],
        ['name' => 'Điện thoại Samsung Galaxy S24 Ultra', 'price' => 26990000, 'img_kw' => 'samsung,smartphone'],
        ['name' => 'Laptop Apple MacBook Air M3 2024', 'price' => 27990000, 'img_kw' => 'macbook'],
        ['name' => 'Laptop Asus Vivobook 15 OLED', 'price' => 15490000, 'img_kw' => 'laptop'],
        ['name' => 'Tai nghe không dây AirPods Pro Gen 2', 'price' => 5890000, 'img_kw' => 'earbuds'],
        ['name' => 'Tai nghe chống ồn Sony WH-1000XM5', 'price' => 7490000, 'img_kw' => 'headphones'],
        ['name' => 'Chuột không dây Logitech MX Master 3S', 'price' => 2450000, 'img_kw' => 'computer,mouse'],
        ['name' => 'Bàn phím cơ không dây Keychron K2 Pro', 'price' => 2150000, 'img_kw' => 'keyboard'],
        ['name' => 'Màn hình LG UltraFine 27 inch 4K', 'price' => 6990000, 'img_kw' => 'monitor,screen'],
        ['name' => 'Loa Bluetooth di động JBL Charge 5', 'price' => 3490000, 'img_kw' => 'speaker,audio']
    ],
    2 => [ // Thời trang
        ['name' => 'Áo thun nam Uniqlo basic', 'price' => 250000, 'img_kw' => 'tshirt'],
        ['name' => 'Áo sơ mi nam dài tay', 'price' => 350000, 'img_kw' => 'shirt'],
        ['name' => 'Quần Jean nam thiết kế', 'price' => 450000, 'img_kw' => 'jeans'],
        ['name' => 'Giày Sneaker Nike Air Force 1', 'price' => 2850000, 'img_url' => 'https://down-vn.img.susercontent.com/file/vn-11134201-7r98o-lstb4zndh38te4'],
        ['name' => 'Giày Tây nam da bò thật', 'price' => 1250000, 'img_kw' => 'leather,shoes'],
        ['name' => 'Áo khoác gió The North Face', 'price' => 750000, 'img_kw' => 'jacket'],
        ['name' => 'Áo Hoodie nỉ nam nữ', 'price' => 280000, 'img_kw' => 'hoodie'],
        ['name' => 'Balo thời trang du lịch', 'price' => 450000, 'img_kw' => 'backpack'],
        ['name' => 'Thắt lưng da bò nam', 'price' => 350000, 'img_kw' => 'belt'],
        ['name' => 'Kính râm thời trang nam nữ', 'price' => 450000, 'img_kw' => 'sunglasses']
    ],
    3 => [ // Đồ ăn
        ['name' => 'Gạo lúa tôm ST25 5kg', 'price' => 165000, 'unit' => 4, 'img_kw' => 'rice,bag'], 
        ['name' => 'Thùng mì tôm Hảo Hảo', 'price' => 115000, 'unit' => 5, 'img_kw' => 'instant,noodles'], 
        ['name' => 'Thùng nước ngọt Coca Cola', 'price' => 195000, 'unit' => 5, 'img_kw' => 'soda,can'],
        ['name' => 'Cà phê G7 3in1 hộp 50 gói', 'price' => 125000, 'unit' => 5, 'img_kw' => 'coffee,box'],
        ['name' => 'Bánh Chocopie Orion', 'price' => 55000, 'unit' => 5, 'img_kw' => 'cake,snack'],
        ['name' => 'Nước mắm Nam Ngư chai 750ml', 'price' => 42000, 'unit' => 1, 'img_kw' => 'sauce,bottle'],
        ['name' => 'Dầu ăn đậu nành Simply 1 lít', 'price' => 54000, 'unit' => 1, 'img_kw' => 'cookingoil'],
        ['name' => 'Sữa chua TH True Milk', 'price' => 28000, 'unit' => 5, 'img_kw' => 'yogurt'],
        ['name' => 'Thịt bò khô xé sợi cay', 'price' => 250000, 'unit' => 4, 'img_kw' => 'beefjerky'],
        ['name' => 'Hạt điều rang muối vỏ lụa', 'price' => 180000, 'unit' => 5, 'img_kw' => 'cashew,nuts']
    ]
];

// Chuẩn bị câu lệnh SQL
$stmt_sp = mysqli_prepare($conn, "INSERT INTO san_pham (ten_san_pham, gia, ton_kho, so_luong_ban, ma_danh_muc, ma_ncc, ma_don_vi, hinh_anh) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt_ls = mysqli_prepare($conn, "INSERT INTO lich_su_gia (ma_san_pham, gia_cu, gia_moi, ngay_thay_doi) VALUES (?, ?, ?, ?)");
$stmt_update_gia = mysqli_prepare($conn, "UPDATE san_pham SET gia = ? WHERE ma_san_pham = ?");

for ($cate_id = 1; $cate_id <= 3; $cate_id++) {
    foreach ($realistic_products[$cate_id] as $base_item) {
        $name = $base_item['name'];
        $base_price = $base_item['price'];
        
        $stock = rand(10, 800);
        $sold = rand(5, $stock); // Đã bán không được vượt quá tồn kho (logic tự quy ước)
        $unit = isset($base_item['unit']) ? $base_item['unit'] : (($cate_id == 2) ? 2 : 1); // 1: Cái, 2: Chiếc
        $supplier = $cate_id;

        // Kiểm tra xem sản phẩm có link ảnh trực tiếp (Shopee CDN) không
        if (isset($base_item['img_url'])) {
            $hinh_anh = $base_item['img_url'];
        } else {
            // Tự động tạo link ảnh minh họa từ thư viện dựa trên keyword
            $keyword = isset($base_item['img_kw']) ? $base_item['img_kw'] : 'product';
            $lock_id = rand(1, 2000);
            $hinh_anh = "https://loremflickr.com/400/400/$keyword/all?lock=$lock_id";
        }

        // Bind và thực thi câu lệnh thêm sản phẩm
        mysqli_stmt_bind_param($stmt_sp, "siiiiiis", $name, $base_price, $stock, $sold, $cate_id, $supplier, $unit, $hinh_anh);
        mysqli_stmt_execute($stmt_sp);
        
        $product_id = mysqli_insert_id($conn);
        $current_price = $base_price;
        
        // Tạo lịch sử giá cho 12 tháng qua
        for ($month = 12; $month >= 1; $month--) {
            $old_price = $current_price;
            
            $percent_change = rand(-15, 20) / 100;
            $current_price = round($old_price * (1 + $percent_change), -3);
            
            $days_ago = $month * 30 - rand(0, 15);
            $date = date('Y-m-d H:i:s', strtotime("-$days_ago days"));

            // Bind và thực thi câu lệnh thêm lịch sử giá
            mysqli_stmt_bind_param($stmt_ls, "iids", $product_id, $old_price, $current_price, $date);
            mysqli_stmt_execute($stmt_ls);
        }
        
        // Cập nhật giá cuối cùng cho sản phẩm
        mysqli_stmt_bind_param($stmt_update_gia, "di", $current_price, $product_id);
        mysqli_stmt_execute($stmt_update_gia);
    }
}

// Đóng các statements
mysqli_stmt_close($stmt_sp);
mysqli_stmt_close($stmt_ls);
mysqli_stmt_close($stmt_update_gia);

echo "Đã thêm 30 sản phẩm và lịch sử giá.<br>";
echo "<hr>";
echo "<h3 style='color: green;'>Đã tạo xong dữ liệu mẫu!</h3>";
echo "<ul>";
echo "<li>3 Danh mục</li>";
echo "<li>3 Nhà cung cấp</li>";
echo "<li>1 Tài khoản Admin (admin / 123456)</li>";
echo "<li>5 Đơn vị tính</li>";
echo "<li>30 Sản phẩm</li>";
echo "<li>360 bản ghi biến động giá trong 1 năm qua để test biểu đồ.</li>";
echo "</ul>";
echo "<a href='../admin/dashboard.php'>Quay lại trang quản trị</a>";
?>