# bai3-ltw-archive

# Product Management Website

Website quản lý sản phẩm được xây dựng bằng PHP thuần và MySQL.

## Chức năng

- Đăng nhập quản trị
- Quản lý sản phẩm
  - Thêm sản phẩm
  - Sửa sản phẩm
  - Xóa sản phẩm
  - Tìm kiếm sản phẩm
- Quản lý danh mục
- Quản lý nhà cung cấp
- Quản lý đơn hàng
- Upload ảnh sản phẩm
- Thống kê:
  - Tổng sản phẩm
  - Tổng đơn hàng
  - Top 3 sản phẩm bán chạy
  - Sản phẩm sắp hết hàng

## Công nghệ sử dụng

- PHP
- MySQL
- HTML5
- CSS3
- JavaScript
- Bootstrap 5

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
│   │
│   └── orders/
│       ├── index.php
│       ├── detail.php
│       └── delete.php
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
