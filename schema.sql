CREATE DATABASE IF NOT EXISTS qlsanpham CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE qlsanpham;

CREATE TABLE don_vi (
    ma_don_vi INT AUTO_INCREMENT PRIMARY KEY,
    ten_don_vi VARCHAR(50) NOT NULL
);

CREATE TABLE danh_muc (
    ma_danh_muc INT AUTO_INCREMENT PRIMARY KEY,
    ten_danh_muc VARCHAR(100) NOT NULL,
    mo_ta TEXT
);

CREATE TABLE nha_cung_cap (
    ma_ncc INT AUTO_INCREMENT PRIMARY KEY,
    ten_ncc VARCHAR(100) NOT NULL,
    so_dien_thoai VARCHAR(20),
    dia_chi VARCHAR(200)
);

CREATE TABLE san_pham (
    ma_san_pham INT AUTO_INCREMENT PRIMARY KEY,
    ten_san_pham VARCHAR(200) NOT NULL,
    gia DECIMAL(15,0) NOT NULL,
    ton_kho INT DEFAULT 0,
    so_luong_ban INT DEFAULT 0,
    mo_ta TEXT,
    hinh_anh VARCHAR(255),
    ma_danh_muc INT,
    ma_ncc INT,
    ma_don_vi INT,
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_danh_muc) REFERENCES danh_muc(ma_danh_muc),
    FOREIGN KEY (ma_ncc) REFERENCES nha_cung_cap(ma_ncc),
    FOREIGN KEY (ma_don_vi) REFERENCES don_vi(ma_don_vi)
);

CREATE TABLE lich_su_gia (
    ma_lich_su INT AUTO_INCREMENT PRIMARY KEY,
    ma_san_pham INT,
    gia_cu DECIMAL(15,0),
    gia_moi DECIMAL(15,0),
    ngay_thay_doi DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_san_pham) REFERENCES san_pham(ma_san_pham)
);

CREATE TABLE users (
    ma_user INT AUTO_INCREMENT PRIMARY KEY,
    ten_dang_nhap VARCHAR(50) NOT NULL UNIQUE,
    mat_khau VARCHAR(255) NOT NULL,
    ho_ten VARCHAR(100),
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- -- Dữ liệu mẫu don_vi
-- INSERT INTO don_vi (ten_don_vi) VALUES ('Cái'), ('Hộp'), ('Kg'), ('Lít'), ('Chiếc');

-- -- Dữ liệu mẫu danh_muc
-- INSERT INTO danh_muc (ten_danh_muc, mo_ta) VALUES
-- ('Điện tử', 'Các thiết bị điện tử'),
-- ('Thực phẩm', 'Đồ ăn uống'),
-- ('Thời trang', 'Quần áo, phụ kiện'),
-- ('Gia dụng', 'Đồ dùng gia đình');

-- -- Dữ liệu mẫu nha_cung_cap
-- INSERT INTO nha_cung_cap (ten_ncc, so_dien_thoai, dia_chi) VALUES
-- ('Công ty TNHH ABC', '0901234567', 'Hà Nội'),
-- ('Công ty CP XYZ', '0987654321', 'TP.HCM'),
-- ('Nhà phân phối 123', '0912345678', 'Đà Nẵng');

-- -- Dữ liệu mẫu san_pham
-- INSERT INTO san_pham (ten_san_pham, gia, ton_kho, so_luong_ban, mo_ta, ma_danh_muc, ma_ncc, ma_don_vi) VALUES
-- ('Tai nghe Sony WH-1000XM5', 8990000, 15, 120, 'Tai nghe chống ồn cao cấp', 1, 1, 1),
-- ('Điện thoại Samsung S24', 22990000, 8, 95, 'Flagship 2024', 1, 2, 1),
-- ('Áo thun Uniqlo', 299000, 50, 80, 'Chất liệu cotton 100%', 3, 3, 5),
-- ('Gạo ST25 5kg', 125000, 200, 300, 'Gạo ngon nhất thế giới', 2, 1, 3),
-- ('Nồi cơm điện Panasonic', 1890000, 5, 45, 'Dung tích 1.8L', 4, 2, 1);