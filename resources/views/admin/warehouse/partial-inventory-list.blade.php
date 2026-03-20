<table class="table table-hover align-middle mb-0">
    <thead class="bg-light">
        <tr>
            <th>Mã SKU</th>
            <th>Tên vật tư</th>
            <th>Vị trí</th>
            <th>Đơn giá vốn</th>
            <th class="text-center">Tồn kho</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @forelse($inventories as $inventory)
            <tr>
                <td class="fw-bold text-muted">{{ $inventory->part->sku }}</td>
                <td>
                    <div class="fw-bold">{{ $inventory->part->name }}</div>
                    <div class="small text-muted">{{ $inventory->part->unit }}</div>
                </td>
                <td>Kệ {{ chr(65 + random_int(0, 4)) }}-{{ random_int(1, 20) }}</td>
                <td>{{ number_format($inventory->part->price_sell ?? 0) }} đ</td>
                <td
                    class="text-center fw-bold {{ $inventory->quantity <= $inventory->min_stock ? 'text-danger' : 'text-primary' }}">
                    {{ $inventory->quantity }}
                </td>
                <td>
                    @if ($inventory->quantity > $inventory->min_stock)
                        <span class="badge bg-success-subtle text-success">Sẵn hàng</span>
                    @else
                        <span class="badge bg-danger-subtle text-danger">Sắp hết (Min:
                            {{ $inventory->min_stock }})</span>
                    @endif
                </td>
                <td>
                    <button class="btn btn-sm btn-light border" onclick="editInventory({{ $inventory->id }})">
                        <i class="fas fa-edit"></i>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center text-muted py-4">Không có dữ liệu</td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- Pagination -->
<!-- Pagination -->
@if ($inventories->total() > $inventories->perPage())
    <div class="card-footer bg-white border-top-0 d-flex justify-content-end py-3">
        <style>
            .pagination-container nav div:first-child {
                display: none !important;
            }

            .pagination-container nav div:last-child {
                display: flex !important;
            }
        </style>

        <div class="pagination-container">
            {{ $inventories->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
