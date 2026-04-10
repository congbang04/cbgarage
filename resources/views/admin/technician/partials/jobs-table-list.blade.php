<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th>Mã phiếu</th>
                    <th>Xe & Khách hàng</th>
                    <th>Dịch vụ yêu cầu</th>
                    <th class="ps-4">Ngày nhận</th>
                    <th class="ps-5">Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($orders as $order)
                    @php
                        $badgeClass = match ($order->status) {
                            'IN_PROGRESS' => '<span class="status-badge info">Đang thực hiện</span>',
                            'WAITING_PART' => '<span class="status-badge warning">Chờ phụ tùng</span>',
                            'COMPLETED' => '<span class="status-badge success">Hoàn thành</span>',
                            default => '<span class="status-badge bg-secondary">Chưa làm</span>',
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
                                        {{ $order->car->carModel->name ?? '' }}
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
                            <div class="text-truncate" style="max-width: 200px;">
                                @forelse ($order->details as $detail)
                                    <div class="small">
                                        <i class="fas fa-check-circle text-muted me-1"></i>
                                        {{ $detail->item->name ?? 'Hạng mục không rõ' }}
                                    </div>
                                @empty
                                    <span class="text-muted">Không có hạng mục</span>
                                @endforelse
                            </div>
                        </td>

                        <td>
                            {{ $order->created_at?->format('d/m/Y H:i') }}
                        </td>

                        <td>
                            {!! $badgeClass !!}
                        </td>

                        <td>
                            <a href="{{ route('technician.jobs.execution', $order->id) }}"
                                class="btn btn-sm btn-primary" style="width: 156px; height: 32px;">
                                @if ($order->status === 'IN_PROGRESS')
                                    <i class="fas fa-play me-1"></i> Cập nhật tiến độ
                                @else
                                    <i class="fas fa-eye"></i> Chi tiết
                                @endif
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Không có công việc được gán
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @include('layouts.partials.pagination', ['items' => $orders])
</div>
