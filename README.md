# bai3-ltw-archive

# Quản lý sản phẩm - PHP thuần

Website quản lý sản phẩm được xây dựng bằng PHP thuần và MySQL.

## Chức năng

- Quản lý sản phẩm
  - Thêm, sửa, xóa sản phẩm
  - Tìm kiếm sản phẩm
  - Upload ảnh sản phẩm
- Quản lý danh mục
- Quản lý nhà cung cấp
- Thống kê:
  - Tổng sản phẩm
  - Top 3 sản phẩm bán chạy
  - Sản phẩm sắp hết hàng
  - Lịch sử thay đổi giá

## Cài đặt

1. Copy thư mục vào `C:\xampp\htdocs\`
2. Import file `sql/database.sql` vào phpMyAdmin
3. Truy cập `http://localhost/bai3php`

## Công nghệ sử dụng

- PHP
- MySQL
- HTML5
- CSS3
- JavaScript

## Cấu trúc thư mục
project/
├── admin/
│   ├── dashboard.php
│   │
│   ├── products/
│   │   ├── index.php
│   │   ├── create.php
│   │   ├── edit.php
│   │   └── delete.php
│   │
│   ├── categories/
│   │   ├── index.php
│   │   ├── create.php
│   │   ├── edit.php
│   │   └── delete.php
│   │
│   ├── suppliers/
│   │   ├── index.php
│   │   ├── create.php
│   │   ├── edit.php
│   │   └── delete.php
│
├── assets/
│   ├── css/
│   ├── js/
│   ├── images/
│   └── uploads/
│
├── config/
│   └── database.php
│
├── includes/
│   ├── header.php
│   ├── footer.php
│   ├── navbar.php
│   └── sidebar.php
│
├── sql/
│   └── database.sql
│
├── index.php
└── login.php
