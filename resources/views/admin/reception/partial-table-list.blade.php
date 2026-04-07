    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="text-center">Mã phiếu</th>
                        <th class="text-center">Xe & Khách hàng</th>
                        <th class="text-center">Kỹ thuật viên</th>
                        <th class="text-center">Ngày nhận</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center">Chi nhánh</th>
                        <th class="text-end">Hành động</th>
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
                            <td class="fw-bold text-primary text-center">
                                #ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                            </td>

                            <td class="text-start">
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

                            <td class="text-start">
                                {{ $order->technician->name ?? '-' }}
                            </td>

                            <td class="text-center">
                                {{ $order->created_at?->format('d/m/Y H:i') }}
                            </td>

                            <td class="text-center">
                                {!! $badgeClass !!}
                            </td>

                            <td class="text-start">
                                {{ $order->branch->name ?? '-' }}
                            </td>

                            <td class="text-end">
                                @php
                                    $btnClass = $order->status === 'RECEIVED' ? 'btn-primary' : 'btn-outline-primary';
                                    $btnIcon = $order->status === 'RECEIVED' ? 'fa-file-invoice-dollar' : 'fa-eye';
                                    $tooltipText = $order->status === 'RECEIVED' ? 'Báo giá' : 'Chi tiết';
                                @endphp

                                @if ($order->status === \App\Models\Order::STATUS_COMPLETED)
                                    <button class="btn btn-success btn-action-fixed btn-pay"
                                        data-order-id="{{ $order->id }}" data-tooltip="Thanh toán">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </button>
                                @endif

                                <a href="{{ route('reception.quotation') }}?orderId={{ $order->id }}"
                                    class="btn {{ $btnClass }} btn-action-fixed"
                                    data-tooltip="{{ $tooltipText }}">
                                    <i class="fas {{ $btnIcon }}"></i>
                                </a>
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
        @include('layouts.partials.pagination', ['items' => $orders])
    </div>
