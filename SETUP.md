# Hướng dẫn Cài đặt Hệ thống Quản lý Sản phẩm

## Yêu cầu hệ thống
- PHP 7.0 trở lên
- MySQL 5.7 trở lên
- Apache Server (XAMPP hoặc tương tự)

## Các bước cài đặt

### 1. Copy dự án vào thư mục htdocs
```bash
Copy thư mục bai3php vào C:\xampp\htdocs\
```

### 2. Tạo cơ sở dữ liệu
- Mở **phpMyAdmin** tại `http://localhost/phpmyadmin`
- Tạo database mới: `qlsanpham`
- Chọn database `qlsanpham` vừa tạo
- Click vào tab **SQL**
- Copy nội dung từ file `sql/database.sql` và paste vào
- Click **Thực thi**

Hoặc dùng dòng lệnh:
```bash
mysql -u root -p < sql/database.sql
```

### 3. Truy cập ứng dụng
- Mở trình duyệt và truy cập: `http://localhost/bai3php`
- Sẽ được chuyển hướng tới trang đăng nhập hoặc dashboard

### 4. Đăng nhập (nếu cần)
- **Username**: admin
- **Password**: admin

## Cấu trúc thư mục

```
bai3php/
├── admin/                          # Thư mục quản trị
│   ├── dashboard.php              # Trang chính
│   ├── products/                  # Quản lý sản phẩm
│   │   ├── index.php
│   │   ├── create.php
│   │   ├── edit.php
│   │   └── delete.php
│   ├── categories/                # Quản lý danh mục
│   │   ├── index.php
│   │   ├── create.php
│   │   ├── edit.php
│   │   └── delete.php
│   ├── suppliers/                 # Quản lý nhà cung cấp
│   │   ├── index.php
│   │   ├── create.php
│   │   ├── edit.php
│   │   └── delete.php
├── assets/
│   ├── css/
│   │   └── style.css             # Stylesheet
│   ├── js/
│   │   └── main.js               # JavaScript
│   ├── images/                   # Hình ảnh tĩnh
│   └── uploads/                  # Ảnh sản phẩm tải lên
├── config/
│   └── database.php              # Cấu hình kết nối database
├── includes/
│   ├── header.php                # Header chung
│   ├── footer.php                # Footer chung
│   ├── sidebar.php               # Menu bên
│   └── navbar.php                # Thanh điều hướng
├── models/
│   └── Database.php              # Helper functions
├── sql/
│   └── database.sql              # Database schema
├── index.php                      # Trang chính
├── login.php                      # Trang đăng nhập
└── README.md                      # Tài liệu
```

## Chức năng chính

### 1. Dashboard
- Thống kê tổng sản phẩm
- Thống kê tổng danh mục
- Cảnh báo sản phẩm sắp hết hàng
- Top 3 sản phẩm bán chạy

### 2. Quản lý Sản phẩm
- Xem danh sách sản phẩm
- Thêm sản phẩm mới (upload ảnh)
- Chỉnh sửa thông tin sản phẩm
- Xóa sản phẩm
- Tìm kiếm sản phẩm
- Theo dõi lịch sử thay đổi giá

### 3. Quản lý Danh mục
- Xem danh sách danh mục
- Thêm danh mục mới
- Chỉnh sửa danh mục
- Xóa danh mục

### 4. Quản lý Nhà cung cấp
- Xem danh sách nhà cung cấp
- Thêm nhà cung cấp mới
- Chỉnh sửa thông tin nhà cung cấp
- Xóa nhà cung cấp

## Lưu ý quan trọng

1. **Quyền truy cập tập tin**: Đảm bảo thư mục `assets/uploads/` có quyền ghi
2. **Kích thước file**: Tối đa 5MB cho ảnh sản phẩm
3. **Định dạng ảnh**: Hỗ trợ JPG, PNG, GIF
4. **Mã hóa**: Sử dụng UTF-8 cho tất cả file

## Khắc phục sự cố

### Lỗi: "Kết nối thất bại"
- Kiểm tra MySQL đã chạy chưa
- Kiểm tra thông tin kết nối trong `config/database.php`

### Lỗi: "Table không tồn tại"
- Chắc chắn đã import file `sql/database.sql`
- Kiểm tra tên database trong `config/database.php`

### Lỗi: Upload ảnh không thành công
- Kiểm tra quyền ghi của thư mục `assets/uploads/`
- Kiểm tra kích thước file (max 5MB)
- Kiểm tra định dạng file

## Bảo mật

1. **Đổi mật khẩu admin**: Cập nhật mật khẩu trong bảng `admin`
2. **SQL Injection**: Dữ liệu được escape trước khi query
3. **XSS Protection**: Sử dụng htmlspecialchars() để output
4. **File Upload**: Kiểm tra loại file trước khi upload

## Hỗ trợ

Nếu gặp vấn đề, vui lòng:
1. Kiểm tra console browser (F12 -> Console)
2. Kiểm tra error log của Apache/PHP
3. Xem chi tiết lỗi trong `VSCODE_TARGET_SESSION_LOG`

---
Được tạo: 2026 | Dự án: Quản lý Sản phẩm PHP
