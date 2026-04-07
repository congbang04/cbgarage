<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">

            {{-- THEAD --}}
            <thead class="bg-light">
                <tr>
                    <th style="width: 120px;" class="text-start">Mã phiếu</th>
                    <th class="text-start">Khách hàng</th>
                    <th class="text-center">Xe</th>
                    <th style="width: 160px;" class="text-center">Bắt đầu</th>
                    <th style="width: 160px;" class="text-center">Kết thúc</th>
                    <th style="width: 140px;" class="text-center">Trạng thái</th>
                    <th class="text-center">Hành động</th>
                </tr>
            </thead>

            {{-- TBODY --}}
            <tbody>
                @forelse ($bookings as $booking)
                    @php
                        $badgeClass = match ($booking->status) {
                            'PENDING' => '<span class="status-badge bg-warning text-dark">Chờ xử lý</span>',
                            'CONFIRMED' => '<span class="status-badge success">Đã tiếp nhận</span>',
                            'CANCELLED' => '<span class="status-badge danger">Đã huỷ</span>',
                            default => '<span class="status-badge bg-dark text-light">Không xác định</span>',
                        };

                        $customerName = $booking->customer->name ?? ($booking->contact_name ?? 'Khách lẻ');
                        $customerPhone = $booking->customer->phone ?? ($booking->contact_phone ?? '-');
                    @endphp

                    <tr>
                        {{-- Mã --}}
                        <td class="fw-bold text-primary text-start">
                            #BK-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}
                        </td>

                        {{-- Khách --}}
                        <td class="text-start">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded p-2 me-2">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">
                                        {{ $customerName }}
                                    </div>
                                    <div class="small text-muted">
                                        {{ $customerPhone }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Xe --}}
                        <td class="text-center">
                            {{ $booking->car->license_plate ?? '---' }}
                        </td>

                        {{-- Start --}}
                        <td class="text-center">
                            {{ $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('d/m/Y H:i') : '-' }}
                        </td>

                        {{-- End --}}
                        <td class="text-center">
                            {{ $booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('d/m/Y H:i') : '-' }}
                        </td>

                        {{-- Status --}}
                        <td class="text-center">
                            {!! $badgeClass !!}
                        </td>

                        {{-- Action --}}
                        <td class="text-center">
                            @php
                                $btnClass = $booking->status === 'PENDING' ? 'btn-primary' : 'btn-outline-primary';
                                $btnText = $booking->status === 'PENDING' ? 'Tiếp nhận' : 'Đã tiếp nhận';
                                $btnIcon = $booking->status === 'PENDING' ? 'fa-plus' : 'fa-check-double';
                                $isDisabled = $booking->status !== 'PENDING';
                            @endphp
                            @if ($booking->status === 'CANCELLED')
                            @else
                                <a href="{{ route('reception.create') }}?bookingId={{ $booking->id }}&phone={{ $booking->contact_phone }}"
                                    class="btn btn-sm {{ $btnClass }} {{ $isDisabled ? 'disabled' : '' }}"
                                    style="width: 140px; height: 40px;">
                                    <i class="fas {{ $btnIcon }} me-1"></i> {{ $btnText }}
                                </a>
                            @endif
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Không có dữ liệu
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    {{-- Pagination --}}
    @include('layouts.partials.pagination', ['items' => $bookings])
</div>
