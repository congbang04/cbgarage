<div class="card h-100">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">Lịch sử phiếu</h5>
    </div>
    <div class="list-group list-group-flush" id="transferHistory" style="max-height: 500px; overflow-y: auto;">
        @forelse($transfers as $transfer)
            <a href="#" class="list-group-item list-group-item-action py-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div>
                        <span class="text-secondary fw-bold me-1">{{ $loop->iteration }}.</span>
                        <strong class="text-primary">#TRF-{{ str_pad($transfer->id, 3, '0', STR_PAD_LEFT) }}</strong>
                    </div>
                    @php
                        $statusClass = match ($transfer->status) {
                            'PENDING' => 'bg-warning text-dark',
                            'COMPLETED' => 'bg-success',
                            'CANCELED' => 'bg-danger',
                            default => 'bg-info text-white',
                        };
                    @endphp
                    <span class="badge {{ $statusClass }}" id="status-{{ $transfer->id }}">
                        {{ match ($transfer->status) {
                            'PENDING' => 'Chờ duyệt',
                            'COMPLETED' => 'Hoàn thành',
                            'CANCELED' => 'Đã hủy',
                            default => 'Đang vận chuyển',
                        } }}
                    </span>
                </div>
                <div class="small text-muted mb-1">Từ: {{ $transfer->fromBranch->name }}</div>
                <div class="small text-muted">{{ $transfer->created_at->format('d/m/Y H:i') }}</div>
            </a>
        @empty
            <div class="p-3 text-center text-muted">
                <i class="fas fa-inbox"></i> Chưa có phiếu chuyển kho
            </div>
        @endforelse
    </div>
    @if (count($transfers) > 0)
        <div class="card-footer bg-white text-center border-top">
            <a href="{{ route('warehouse.transfer.history') }}" class="text-decoration-none small fw-bold">
                Xem tất cả lịch sử <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    @endif
</div>
