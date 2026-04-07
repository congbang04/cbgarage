<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            {{-- THEAD --}}
            <thead class="bg-light">
                <tr>
                    <th class="text-start">Mã phiếu</th>
                    <th class="text-start">Khách hàng</th>
                    <th class="text-center">Xe</th>
                    <th class="text-center">Bắt đầu</th>
                    <th class="text-center">Kết thúc</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($bookings as $booking)
                    @php
                        $badgeClass = match ($booking['status']) {
                            'PENDING' => 'bg-info text-white',
                            'CONFIRMED' => 'bg-success text-white',
                            'CANCELED' => 'bg-danger text-white',
                            default => 'bg-dark text-light',
                        };
                    @endphp
                    <tr>
                        {{-- Mã Phiếu --}}
                        <td class="fw-bold text-primary text-start">
                            #BK-{{ $booking['code'] }}
                        </td>

                        {{-- Khách Hàng --}}
                        <td class="text-start">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded p-2 me-2">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">
                                        {{ $booking['customer_name'] }}
                                    </div>
                                    <div class="small text-muted">
                                        {{ $booking['customer_phone'] }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Xe --}}
                        <td class="text-center">
                            <strong>{{ $booking['car_plate'] }}</strong>
                        </td>

                        {{-- Bắt Đầu --}}
                        <td class="text-center">
                            {{ $booking['start_time'] }}
                        </td>

                        {{-- Kết Thúc --}}
                        <td class="text-center">
                            {{ $booking['end_time'] }}
                        </td>

                        {{-- Trạng Thái --}}
                        <td class="text-center">
                            <span class="status-badge {{ $badgeClass }}">
                                {{ $booking['status_name'] }}
                            </span>
                        </td>

                        {{-- Hành Động --}}
                        <td class="text-center">
                            @if ($booking['can_cancel'])
                                <button class="btn btn-danger btn-sm btn-cancel" data-id="{{ $booking['id'] }}"
                                    title="Hủy lịch đặt" style="width: 110px; height:32px">
                                    <i class="fas fa-times me-1"></i>Hủy lịch
                                </button>
                            @elseif ($booking['status'] === 'CANCELED' || $booking['status'] === 'CONFIRMED')
                                <button class="btn btn-outline-secondary btn-sm"
                                    title="Không thể hủy lịch đã được tiếp nhận hoặc đã hủy"
                                    style="width: 110px; height:32px">
                                    <i class="fas fa-lock me-1"></i>Khóa
                                </button>
                            @else
                                <button class="btn btn-secondary btn-sm" title="Quá hạn hủy (chỉ hủy được trong 24h)"
                                    style="width: 110px; height:32px">
                                    <i class="fas fa-lock me-1"></i>Quá hạn
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                                <p>Bạn chưa có lịch đặt nào</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($bookings->hasPages())
        <div class="card-footer bg-white">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
