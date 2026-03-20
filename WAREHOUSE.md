# Warehouse Management System - Implementation Guide

## Giới Thiệu

Hệ thống quản lý kho (Warehouse Management System) cho KLTN-Garage đã được xây dựng hoàn chỉnh theo architecture pattern giống như chức năng Reception.

**Chức năng chính:**

1. **Quản lý tồn kho** - Xem hàng tồn, tìm kiếm, lọc theo danh mục
2. **Duyệt yêu cầu xuất kho** - Duyệt/từ chối yêu cầu từ kỹ thuật viên
3. **Phiếu xuất kho** - Tạo phiếu xuất hàng (có thể in)
4. **Luân chuyển kho** - Chuyển hàng giữa các chi nhánh

---

## Tổng Quan Cấu Trúc

```
Backend:
app/
├── Http/Controllers/WarehouseController.php
├── Http/Requests/
│   ├── PartRequestRequest.php
│   └── StockTransferRequest.php
├── Services/
│   ├── WarehouseService.php
│   ├── PartRequestService.php
│   └── StockTransferService.php
└── Events/
    ├── PartRequestUpdated.php
    └── StockTransferUpdated.php

Frontend:
resources/
├── views/admin/warehouse/
│   ├── inventory.blade.php
│   ├── part-requests.blade.php
│   ├── goods-issue.blade.php
│   ├── stock-transfer.blade.php
│   ├── partial-inventory-list.blade.php
│   └── sidebar.blade.php
└── js/modules/warehouse/
    ├── inventory.js
    ├── part-requests.js
    └── stock-transfer.js

Database:
database/seeders/
├── InventorySeeder.php
├── PartRequestSeeder.php
└── StockTransferSeeder.php
```

---

## Cách Sử Dụng

### 1. Setup Database

**Chạy migrations (nếu chưa):**

```bash
php artisan migrate
```

**Hoặc reset + migrate lại:**

```bash
php artisan migrate:fresh
```

### 2. Seed Dữ Liệu Test

```bash
# Seed tất cả
php artisan db:seed --class=InventorySeeder
php artisan db:seed --class=PartRequestSeeder
php artisan db:seed --class=StockTransferSeeder
```

### 3. Truy Cập Chức Năng

| Tên Chức Năng      | URL                           | Quyền           |
| ------------------ | ----------------------------- | --------------- |
| Quản lý tồn kho    | `/warehouse/inventory`        | Auth + Verified |
| Duyệt yêu cầu xuất | `/warehouse/part-requests`    | Auth + Verified |
| Phiếu xuất kho     | `/warehouse/goods-issue/{id}` | Auth + Verified |
| Luân chuyển kho    | `/warehouse/stock-transfer`   | Auth + Verified |

### 4. Quy Trình Làm Việc

#### A. Quản Lý Tồn Kho

1. Truy cập `/warehouse/inventory`
2. Xem danh sách phụ tùng tồn trong kho tổng
3. Tìm kiếm theo tên hoặc mã SKU
4. Xem số lượng tối thiểu và số tồn hiện tại
5. Báo cáo hàng sắp hết (đỏ = sắp hết, xanh = sẵn hàng)

#### B. Duyệt Yêu Cầu Xuất Kho

1. Truy cập `/warehouse/part-requests`
2. Xem các yêu cầu từ kỹ thuật viên (dạng card)
3. Mỗi card hiển thị:
    - Mã yêu cầu (#REQ-XXXXX)
    - Tên kỹ thuật viên
    - Xe đang sửa
    - Danh sách phụ tùng đang yêu cầu
    - Nút "Xuất kho" hoặc "Từ chối"

#### C. Phiếu Xuất Kho (Goods Issue)

1. Click "Xuất kho" từ yêu cầu → mở form chi tiết
2. Xem thông tin chi tiết phiếu:
    - Kho nguồn
    - Người yêu cầu
    - Lý do xuất (xe + phiếu order)
    - Ghi chú từ kỹ thuật viên
3. **Nhập số lượng thực tế**: Có thể điều chỉnh số lượng nếu không đủ
4. Click "Xác nhận xuất" → hoàn thành
5. Hoặc click "In phiếu" để in ra giấy

#### D. Luân Chuyển Kho

1. Truy cập `/warehouse/stock-transfer`
2. Form tạo phiếu:
    - Chọn "Kho nguồn" (Kho Tổng)
    - Chọn "Kho đích" (Chi nhánh muốn nhập)
    - Click "+ Thêm dòng" để thêm phụ tùng
    - Chọn phụ tùng và nhập số lượng
    - Nhập ghi chú (tuỳ chọn)
    - Click "Gửi yêu cầu"
3. Xem "Lịch sử phiếu" ở bên phải
    - Trạng thái: Chờ gửi / Đang vận chuyển / Hoàn thành

---

## Architecture & Code Patterns

### Backend Pattern: Service Layer

**Flow:**

```
HTTP Request
    ↓
WarehouseController (thin, chỉ validate & call service)
    ↓
WarehouseService / PartRequestService / StockTransferService (business logic)
    ↓
Models (persistence)
    ↓
Event::broadcast (real-time update)
```

**Ví dụ:**

```php
// Controller - Thin
public function approvePartRequest(PartRequestRequest $request, $partRequestId)
{
    $goods = $this->partRequestService->issueGoods(
        $partRequestId,
        $request->validated()
    );
    return response()->json(['message' => '...']);
}

// Service - Fat (business logic)
public function issueGoods(int $partRequestId, array $data): array
{
    return DB::transaction(function () use ($partRequestId, $data) {
        $partRequest = $this->getPartRequestById($partRequestId);
        // Validate stock
        foreach ($partRequest->details as $detail) {
            $this->deductInventory($detail->part_id, $actualQty);
        }
        // Update status
        $partRequest->update(['status' => 'completed']);
        // Broadcast real-time
        broadcast(new \App\Events\PartRequestUpdated($partRequest));
        return $goodsIssue;
    });
}
```

### Frontend Pattern: Modular JS

**Nguyên tắc:**

- ✅ Blade templates render HTML
- ✅ JS modules chỉ handle events & API calls
- ✅ API services wrapper (jQuery $.ajax)
- ✅ Real-time updates via Echo listening

**Cấu trúc file:**

```javascript
// resources/js/services/warehouse-api.js
export function approvePartRequest(id, data) {
    return $.ajax({ ... });
}

// resources/js/modules/warehouse/part-requests.js
window.approveRequest = function(id) {
    // Navigate to goods issue
    window.location.href = `/warehouse/goods-issue/${id}`;
};

// Listen for real-time updates
window.Echo.channel('part-requests').listen('PartRequestUpdated', (event) => {
    updateRequestCard(event.partRequest);
});
```

### Database Transactions

Tất cả thao tác quan trọng (deduct inventory, transfer stock) dùng transactions:

```php
DB::transaction(function () {
    // Kiểm tra stock đủ không
    // Thực hiện deduct/transfer
    // Update status
    // Broadcast event
});
```

---

## Models & Relationships

```php
// Inventory - Tồn kho
- Inventory belongsTo Part
- Inventory belongsTo Branch

// Part & PartRequest
- Part hasMany Inventory
- Part hasManyThrough PartRequestDetail

// PartRequest - Yêu cầu xuất
- PartRequest belongsTo Order
- PartRequest belongsTo Technician
- PartRequest hasMany PartRequestDetail

// PartRequestDetail - Chi tiết yêu cầu
- PartRequestDetail belongsTo Part
- PartRequestDetail belongsTo PartRequest

// StockTransfer - Chuyển kho
- StockTransfer belongsTo Branch (from/to)
- StockTransfer belongsTo User (creator)
- StockTransfer belongsToMany Part (via stock_transfer_details)
```

---

## Validation Rules

### PartRequestRequest

```php
[
    'part_request_id' => 'required|exists:part_requests,id',
    'actual_quantities' => 'required|array',
    'actual_quantities.*' => 'required|integer|min:0',
    'notes' => 'nullable|string|max:255',
]
```

### StockTransferRequest

```php
[
    'from_branch_id' => 'required|exists:branches,id',
    'to_branch_id' => 'required|exists:branches,id|different:from_branch_id',
    'items' => 'required|array|min:1',
    'items.*.part_id' => 'required|exists:parts,id',
    'items.*.quantity' => 'required|integer|min:1',
    'note' => 'nullable|string|max:255',
]
```

---

## API Endpoints

### Warehouse (GET)

- `GET /warehouse/inventory` - Trang chính
- `GET /warehouse/inventory-list` - Danh sách (AJAX)
- `GET /warehouse/part-requests` - Yêu cầu xuất
- `GET /warehouse/goods-issue/{id}` - Chi tiết phiếu
- `GET /warehouse/stock-transfer` - Chuyển kho
- `GET /warehouse/parts` - Danh sách phụ tùng (JSON)

### Warehouse (POST)

- `POST /warehouse/approve-part-request/{id}` - Duyệt
- `POST /warehouse/reject-part-request/{id}` - Từ chối
- `POST /warehouse/stock-transfer/create` - Tạo chuyển kho

---

## Broadcasting Events

### PartRequestUpdated

```php
Event::dispatch(new PartRequestUpdated($partRequest));
```

Broadcast channel: `part-requests`
Event name: `PartRequestUpdated`

JS listener:

```javascript
Echo.channel("part-requests").listen("PartRequestUpdated", (event) => {
    console.log("Request status changed:", event.partRequest);
});
```

### StockTransferUpdated

Channel: `stock-transfers`
Event name: `StockTransferUpdated`

---

## Troubleshooting

### Problem: Seeder không chạy

**Solution:**

```bash
php artisan migrate:fresh
php artisan db:seed --class=InventorySeeder
```

### Problem: Foreign key error

**Solution:** Đảm bảo foreign keys constraint:

```bash
php artisan migrate --refresh
```

### Problem: Stock quantity thành negative

**Solution:** Check xem có transaction không, hoặc validate stock availability trước khi deduct

### Problem: Real-time updates không hoạt động

**Solution:**

- Kiểm tra Reverb server đang chạy: `php artisan reverb:start`
- Check js/echo.js configuration

---

## File Checklist

✅ Controllers: `WarehouseController.php`
✅ Services: `WarehouseService.php`, `PartRequestService.php`, `StockTransferService.php`
✅ Requests: `PartRequestRequest.php`, `StockTransferRequest.php`
✅ Views: 6 Blade files
✅ JS Modules: 3 modules + 1 API service
✅ Events: `PartRequestUpdated.php`, `StockTransferUpdated.php`
✅ Seeders: 3 seeders
✅ Routes: Routes added to `web.php`
✅ Migrations: All tables created + 1 fix migration

---

## Integration dengan Reception Feature

Cả Reception và Warehouse đều dùng:

- ✅ Service Layer pattern
- ✅ Thin Controllers
- ✅ Database Transactions
- ✅ Event Broadcasting
- ✅ Blade Partials (không render HTML trong JS)
- ✅ Request Validation
- ✅ API Services wrapper

---

## Tài Liệu Thêm

- [RECEPTION_BLUEPRINT.md](RECEPTION_BLUEPRINT.md) - Architecture reception
- [AI_RULES.md](.vscode/AI_RULES.md) - Laravel coding standards
- [AI_FRONTEND_RULES.md](.vscode/AI_FRONTEND_RULES.md) - Frontend standards

---

**Created:** March 21, 2026
**Last Updated:** March 21, 2026  
**Status:** ✅ Complete & Ready for Testing
