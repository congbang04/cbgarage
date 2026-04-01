<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-3">Mã phiếu</th>
                    <th>Kho xuất (Từ)</th>
                    <th>Kho nhập (Đến)</th>
                    <th>Ngày tạo</th>
                    <th>Trạng thái</th>
                    <th class="text-end pe-3">Hành động</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($transfers as $transfer)
                    @php
                        // Set class cho Badge giống cách bạn làm ở Reception
                        $badgeClass = match ($transfer->status) {
                            'PENDING' => '<span class="status-badge warning">Chờ duyệt</span>',
                            'IN_TRANSIT'
                                => '<span class="status-badge bg-primary-subtle text-primary">Đang vận chuyển</span>',
                            'COMPLETED' => '<span class="status-badge success">Hoàn thành</span>',
                            'CANCELED' => '<span class="status-badge danger">Đã huỷ</span>',
                            default => '<span class="status-badge bg-dark text-light">Không xác định</span>',
                        };
                    @endphp
                    <tr>
                        <td class="fw-bold text-primary ps-3">
                            #TRF-{{ str_pad($transfer->id, 4, '0', STR_PAD_LEFT) }}
                        </td>

                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded p-2 me-2">
                                    <i class="fas fa-arrow-up text-danger"></i>
                                </div>
                                <div class="fw-bold">
                                    {{ $transfer->fromBranch->name ?? 'N/A' }}
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded p-2 me-2">
                                    <i class="fas fa-arrow-down text-success"></i>
                                </div>
                                <div class="fw-bold">
                                    {{ $transfer->toBranch->name ?? 'N/A' }}
                                </div>
                            </div>
                        </td>

                        <td>
                            {{ $transfer->created_at?->format('d/m/Y H:i') }}
                        </td>

                        <td>
                            {!! $badgeClass !!}
                        </td>

                        <td class="text-end pe-3">
                            {{-- Trỏ đến Route xem chi tiết phiếu xuất (nếu bạn đã có) --}}
                            <button class="btn btn-sm btn-outline-primary"
                                onclick="transferDetail({{ $transfer->id }}), console.log({{ $transfer->id }})">
                                <i class="fas fa-eye"></i> Chi tiết
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Không có dữ liệu lịch sử điều chuyển
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @include('layouts.partials.pagination', ['items' => $transfers])
</div>
