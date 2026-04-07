<table class="table table-hover align-middle">
    <thead class="table-light">
        <tr>
            <th class="text-center" style="width: 60px;">ID</th>
            <th>Tên khách hàng</th>
            <th>Số điện thoại</th>
            <th>Email</th>
            <th>Số xe</th>
            <th class="text-center">Ngày tạo</th>
            <th class="text-center" style="width: 140px;">Hành động</th>
        </tr>
    </thead>
    <tbody>
        @forelse($customers as $customer)
            <tr>
                <td>
                    <span class="badge bg-secondary" style="width: 60px">#{{ $customer->id }}</span>
                </td>
                <td style="width: 350px">
                    <strong>{{ $customer->name }}</strong>
                    @if ($customer->address)
                        <br>
                        <small class="text-muted">
                            <i class="fa-solid fa-location-dot"></i>
                            {{ $customer->address }}
                        </small>
                    @endif
                </td>
                <td>
                    {{ $customer->phone }}
                </td>
                <td>
                    @if ($customer->user?->email)
                        {{ $customer->user?->email }}
                    @else
                        <span class="text-muted">--</span>
                    @endif
                </td>
                <td>
                    <span class="badge bg-info">
                        <i class="fas fa-car"></i> {{ $customer->cars->count() }}
                    </span>
                </td>
                <td>
                    <small class="text-muted">{{ $customer->created_at->format('d/m/Y H:i') }}</small>
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-sm btn-outline-info"
                            title="Xem chi tiết">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-warning edit-customer"
                            data-customer='@json($customer)' title="Chỉnh sửa">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger delete-customer"
                            data-customer-id="{{ $customer->id }}" data-customer-name="{{ $customer->name }}"
                            title="Xóa">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center py-5">
                    <i class="fas fa-inbox" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="text-muted mt-3">Không có khách hàng nào</p>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@include('layouts.partials.pagination', ['items' => $customers])
</div>
