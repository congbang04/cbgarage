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
            <td class="text-center">{{ $index + 1 }}</td>
            <td>
                <div class="text-start fw-bold name-item">{{ $detail->item->name ?? 'N/A' }} </div>
            </td>
            <td class="text-center">{!! $badgeClass !!}</td>
            <td class="text-center">cái</td>
            <td class="text-center">
                <input class="text-center" style="max-width: 60px;" disabled value="{{ $detail->quantity }}"
                    data-index="{{ $index + 1 }}">
            </td>
            <td class="text-center item-price">{{ number_format($detail->price, 0, ',', '.') }}</td>
            <td class="text-end fw-bold item-total">{{ number_format($lineTotal, 0, ',', '.') }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="100%" class="text-center">
                <div id="empty-state" class="p-4 text-center text-muted">
                    <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                    <p>Chưa có hạng mục nào.</p>
                </div>
            </td>
        </tr>
    @endforelse
