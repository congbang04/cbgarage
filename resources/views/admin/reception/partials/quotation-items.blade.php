    <input type="hidden" id="technician_id" value="{{ $technicianId ?? '' }}">
    @forelse ($items as $index => $detail)
        @php
            $lineTotal =
                $detail->quantity * $detail->price - $detail->quantity * $detail->price * ($detail->discount / 100);
            $badgeClass =
                $detail->item_type === 'App\Models\Service'
                    ? '<span class="badge bg-info-subtle text-info">Dịch vụ</span>'
                    : '<span class="badge bg-secondary-subtle text-secondary">Vật tư</span>';
        @endphp
        <tr data-item-id="{{ $detail->item_type }}-{{ $detail->item_id }}" data-index="{{ $index + 1 }}">
            <td>{{ $index + 1 }}</td>
            <td>
                <div class="fw-bold name-item">{{ $detail->item->name ?? 'N/A' }} </div>
                <div class="small text-muted">{{ $detail->item_id }}</div>
            </td>
            <td class="text-start">{!! $badgeClass !!}</td>
            <td>cái</td>
            <td>
                <input class="form-control form-control-sm text-center item-quantity" disabled
                    value="{{ $detail->quantity }}" data-index="{{ $index + 1 }}">
            </td>
            <td class="text-end item-price">{{ number_format($detail->price, 0, ',', '.') }}</td>
            <td class="text-end fw-bold item-total">{{ number_format($lineTotal, 0, ',', '.') }}</td>
            <td>
                <button type="button" class="btn btn-sm text-danger btn-remove-item" data-index="{{ $index + 1 }}">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="100%" class="text-center">
                <div id="empty-state" class="p-4 text-center text-muted">
                    <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                    <p>Chưa có hạng mục nào. Nhấn nút "Thêm" để bắt đầu.</p>
                </div>
            </td>
        </tr>
    @endforelse
