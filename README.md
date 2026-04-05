# Hệ thống quản lý dịch vụ và đặt lịch sửa chữa ô tô

Đây là hệ thống web hỗ trợ quản lý dịch vụ và đặt lịch sửa chữa cho chuỗi garage ô tô.
Hệ thống cho phép khách hàng đặt lịch trực tuyến, đồng thời hỗ trợ quản lý lịch hẹn,
dịch vụ và khách hàng cho nhân viên và quản trị viên.

Ngoài ra, hệ thống tích hợp trợ lý ảo giúp tư vấn dịch vụ tự động cho khách hàng.

## Vai trò người dùng

- Khách hàng (Customer)
- Nhân viên (Staff)
- Quản trị viên (Admin)

## Chức năng khách hàng

- Đăng ký, đăng nhập tài khoản
- Cập nhật thông tin cá nhân
- Quản lý xe
- Xem danh sách dịch vụ sửa chữa
- Đặt lịch sửa chữa theo ngày giờ
- Chọn chi nhánh garage
- Theo dõi trạng thái đơn hàng sửa chữa xe
- Xem lịch sử sửa chữa
- Nhận tư vấn từ trợ lý ảo

## Chức năng nhân viên

- Xem danh sách lịch hẹn
- Cập nhật trạng thái lịch (pending, confirmed, completed...)
- Xem thông tin khách hàng và dịch vụ
- Quản lý lịch làm việc

## Chức năng quản trị

- Quản lý người dùng (khách hàng, nhân viên)
- Quản lý dịch vụ sửa chữa
- Quản lý chi nhánh garage
- Quản lý lịch hẹn toàn hệ thống
- Theo dõi trạng thái và lịch sử sửa chữa

## Tính năng nổi bật

- Đặt lịch sửa chữa trực tuyến
- Quản lý đa chi nhánh garage
- Lọc và phân loại lịch theo trạng thái
- Giao diện thân thiện, dễ sử dụng
- Tích hợp trợ lý ảo tư vấn tự động

## Công nghệ sử dụng

- Backend: Laravel
- Frontend: HTML, CSS, JavaScript
- Cơ sở dữ liệu: MySQL
- AJAX: xử lý lọc và cập nhật dữ liệu không reload trang

## Hướng dẫn cài đặt

1. Clone project
2. Cài đặt composer
3. Cấu hình file .env
4. Chạy migrate database
5. Start server
