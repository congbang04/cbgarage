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
- Đặt lịch sửa chữa
- Theo dõi trạng thái đơn hàng sửa chữa xe
- Xem lịch sử sửa chữa
- Nhận tư vấn từ trợ lý ảo

---

## Chức năng nhân viên

### Cố vấn dịch vụ (Advisor)

- Tiếp nhận khách hàng và tạo phiếu sửa chữa
- Tạo và quản lý báo giá
- Xem danh sách dịch vụ, phụ tùng, tiền công, kỹ thuật viên
- Thêm dịch vụ, phụ tùng, tiền công vào báo giá
- Gán kỹ thuật viên thực hiện
- Lưu và cập nhật thông tin báo giá theo từng đơn

### Kỹ thuật viên (Technician)

- Xem danh sách công việc được phân công
- Xem chi tiết công việc theo từng xe
- Cập nhật trạng thái công việc (đang làm, hoàn thành,...)
- Hoàn thành từng hạng mục sửa chữa
- Có thể yêu cầu thêm phụ tùng trong quá trình sửa chữa

### Nhân viên kho (Warehouse)

- Quản lý tồn kho phụ tùng, vật tư
- Xem danh sách yêu cầu phụ tùng, vật tư từ kỹ thuật viên
- Duyệt / từ chối yêu cầu phụ tùng, vật tư
- Quản lý xuất kho và điều chuyển kho
- Theo dõi lịch sử nhập – xuất – chuyển kho
- Xử lý các yêu cầu chuyển kho giữa các chi nhánh

### Quản lý thanh toán (Cashier)

- Xem danh sách thanh toán
- Xem chi tiết đơn hàng
- Xử lý thanh toán
- Hoàn tiền (refund)
- Theo dõi trạng thái thanh toán

---

### Quản lý hệ thống (Super Admin)

> Là cấp quản trị cao nhất, có toàn quyền trên toàn bộ hệ thống.

#### Bao gồm

- Toàn bộ chức năng của **Nhân viên**
- Toàn bộ chức năng của **Branch Admin**

#### Chức năng nâng cao

- Quản lý khách hàng
- Quản lý dịch vụ sửa chữa
- Quản lý phụ tùng, vật tư (inventory)
- Quản lý chi nhánh (branches)
- Quản lý cầu nâng (bay) theo từng chi nhánh
- Quản lý nhân viên:
    - Thêm / sửa / xoá
    - Bật / tắt trạng thái hoạt động
- Phân quyền hệ thống:
    - Gán vai trò (role)
    - Kiểm soát quyền truy cập
- Quản lý trợ lý ảo:
    - Xem danh sách phiên chat
    - Xem nội dung hội thoại
    - Xoá phiên chat

---

### Quản trị chi nhánh (Branch Admin)

> Quản lý hoạt động trong phạm vi **một chi nhánh cụ thể**.

#### Bao gồm

- Toàn bộ chức năng của **Nhân viên**

#### Giới hạn

- Không có quyền quản lý toàn hệ thống (multi-branch)
- Không quản lý nhân sự toàn hệ thống
- Không truy cập dữ liệu của chi nhánh khác

---

## Tính năng nổi bật

- Đặt lịch sửa chữa trực tuyến
- Quản lý đa chi nhánh garage
- Lọc và phân loại lịch theo trạng thái
- Giao diện thân thiện, dễ sử dụng
- Tích hợp trợ lý ảo tư vấn tự động

## Công nghệ sử dụng

- Backend: PHP (Laravel Framework), RESTful API, MVC Architecture
- Frontend: HTML5, CSS3, JavaScript (ES6+), Blade Template Engine, jQuery (xử lý DOM, AJAX)
- Xử lý bất đồng bộ: AJAX (jQuery), Lọc dữ liệu không reload trang, Cập nhật trạng thái realtime (booking, đơn sửa chữa)
- Cơ sở dữ liệu: MySQL
- DevOps / Môi trường: Docker (Dockerfile)
