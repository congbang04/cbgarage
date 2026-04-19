    <input type="hidden" id="technician_id" value="{{ $technicianId ?? '' }}">
    @forelse ($items as $index => $detail)
        @php
            $lineTotal = $detail->quantity * $detail->price;
            $badgeClass = match ($detail->item_type) {
                'App\Models\Service'
                    => '<span class="badge bg-info-subtle text-info fw-bold" style="width: 75px">Dịch vụ</span>',
                'App\Models\Labor'
                    => '<span class="badge bg-primary-subtle text-primary fw-bold" style="width: 75px">Tiền công</span>',
                default
                    => '<span class="badge bg-secondary-subtle text-secondary fw-bold" style="width: 75px">Vật tư</span>',
            };
        @endphp
        <tr data-item-id="{{ $detail->item_type }}-{{ $detail->item_id }}" data-index="{{ $index + 1 }}"
            id="quotation-items">
            <td class="text-center">{{ $index + 1 }}</td>
            <td class="text-start">
                <div class="fw-bold name-item">{{ $detail->item->name ?? 'N/A' }} </div>
                <div class="small text-muted">id: {{ $detail->item_id }}</div>
            </td>
            <td class="text-center">{!! $badgeClass !!}</td>
            <td class="text-center">{{ $detail->item?->unit ?? '-' }}</td>
            <td class="text-center">
                <input class="text-center item-quantity" style="max-width: 60px;" disabled
                    value="{{ $detail->quantity }}" data-index="{{ $index + 1 }}">
            </td>
            <td class="text-end item-price">{{ number_format($detail->price, 0, ',', '.') }}</td>
            <td class="text-end fw-bold item-total">{{ number_format($lineTotal, 0, ',', '.') }}</td>
            <td class="text-end">
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
