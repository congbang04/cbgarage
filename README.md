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
- Cập nhật thông tin
- Quản lý xe
- Xem danh sách dịch vụ sửa chữa
- Đặt lịch sửa chữa theo ngày giờ
- Chọn chi nhánh
- Theo dõi trạng thái đơn hàng sửa chữa xe
- Xem lịch sử sửa chữa
- Nhận tư vấn từ trợ lý ảo

## Chức năng nhân viên

### 👨‍🔧 Lễ tân (Reception)

- Tiếp nhận khách hàng và tạo phiếu sửa chữa
- Tạo và quản lý báo giá (quotation)
- Thêm dịch vụ và phụ tùng vào báo giá
- Gán kỹ thuật viên thực hiện
- Xem danh sách dịch vụ, phụ tùng, kỹ thuật viên
- Lưu và cập nhật thông tin báo giá theo từng đơn

---

### 🧑‍🔧 Kỹ thuật viên (Technician)

- Xem danh sách công việc được phân công
- Xem chi tiết công việc theo từng xe
- Cập nhật trạng thái công việc (đang làm, hoàn thành,...)
- Hoàn thành từng hạng mục sửa chữa
- Yêu cầu thêm phụ tùng trong quá trình sửa chữa

---

### 📦 Nhân viên kho (Warehouse)

- Quản lý tồn kho phụ tùng
- Xem danh sách yêu cầu phụ tùng từ kỹ thuật viên
- Duyệt / từ chối yêu cầu phụ tùng
- Quản lý xuất kho và điều chuyển kho
- Theo dõi lịch sử nhập – xuất – chuyển kho
- Xử lý các yêu cầu chuyển kho giữa các chi nhánh

---

## Chức năng quản trị

### 🛠️ Quản lý hệ thống (Super Admin)

- Quản lý chi nhánh garage
- Quản lý dịch vụ sửa chữa
- Quản lý phụ tùng
- Quản lý nhân viên (thêm, sửa, xoá, bật/tắt hoạt động)
- Phân quyền và kiểm soát trạng thái nhân viên

---

### 📊 Quản trị vận hành (Admin)

- Xem dashboard thống kê hệ thống
- Tìm kiếm và quản lý khách hàng
- Quản lý toàn bộ lịch hẹn (booking)
- Theo dõi trạng thái sửa chữa của các đơn hàng
- Xem dữ liệu thống kê theo thời gian thực

---

### 💳 Quản lý thanh toán

- Xem danh sách thanh toán
- Xem chi tiết đơn hàng
- Xử lý thanh toán
- Hoàn tiền (refund)
- Theo dõi trạng thái thanh toán

---

### 🤖 Quản lý trợ lý ảo (AI Chat)

- Xem danh sách phiên chat
- Xem nội dung hội thoại
- Xoá phiên chat
- Theo dõi hoạt động tư vấn của hệ thống AI

---

## Các chức năng liên quan khác

- Đặt lịch sửa chữa trực tuyến (Booking)
- Quản lý xe và lịch sử sửa chữa của khách hàng
- Xem khung giờ (slots) khả dụng
- Chat với trợ lý ảo để được tư vấn dịch vụ

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
