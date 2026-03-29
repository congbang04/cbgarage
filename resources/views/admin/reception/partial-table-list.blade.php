    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Mã phiếu</th>
                        <th>Xe & Khách hàng</th>
                        <th>Kỹ thuật viên</th>
                        <th class="ps-4">Ngày nhận</th>
                        <th class="ps-5">Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($orders as $order)
                        @php
                            $badgeClass = match ($order->status) {
                                'RECEIVED' => '<span class="status-badge info">Đã tiếp nhận</span>',

                                'QUOTED' => '<span class="status-badge warning">Đã báo giá</span>',

                                'IN_PROGRESS'
                                    => '<span class="status-badge bg-primary-subtle text-primary">Đang thực hiện</span>',

                                'WAITING_PART'
                                    => '<span class="status-badge bg-secondary-subtle text-secondary">Chờ phụ tùng</span>',

                                'COMPLETED' => '<span class="status-badge success">Hoàn thành</span>',

                                'PAID' => '<span class="status-badge bg-success text-light">Đã thanh toán</span>',

                                'CANCELLED' => '<span class="status-badge danger">Đã huỷ</span>',

                                default => '<span class="status-badge bg-dark text-light">Không xác định</span>',
                            };
                        @endphp
                        <tr>
                            <td class="fw-bold text-primary">
                                #ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                            </td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded p-2 me-3">
                                        <i class="fas fa-car"></i>
                                    </div>

                                    <div>
                                        <div class="fw-bold">
                                            {{ $order->car->brand->name ?? '' }}
                                            {{ $order->car->model->name ?? '' }}
                                            - {{ $order->car->license_plate ?? '' }}
                                        </div>

                                        <div class="small text-muted">
                                            {{ $order->customer->name ?? '' }}
                                            - {{ $order->customer->phone ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                {{ $order->technician->name ?? '-' }}
                            </td>

                            <td>
                                {{ $order->created_at?->format('d/m/Y H:i') }}
                            </td>

                            <td>
                                {!! $badgeClass !!}
                            </td>

                            <td>
                                @if ($order->status === 'RECEIVED')
                                    <a href="{{ route('reception.quotation') }}?orderId={{ $order->id }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="fas fa-file-invoice-dollar me-1"></i> Lập báo giá
                                    </a>
                                @else
                                    <a href="{{ route('reception.quotation') }}?orderId={{ $order->id }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> Chi tiết
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Không có dữ liệu
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <!-- Pagination -->
        @if ($orders->total() > $orders->perPage())
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
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
