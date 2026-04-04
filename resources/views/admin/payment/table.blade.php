<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="bg-light">
            <tr>
                <th class="text-start">STT</th>
                <th class="text-center">Mã đơn hàng</th>
                <th class="text-center">Khách hàng</th>
                <th class="text-center">Chi nhánh</th>
                <th class="text-center">Loại</th>
                <th class="text-center" class="text-end">Số tiền</th>
                <th class="text-center">Hình thức</th>
                <th class="text-center">Thời gian</th>
                <th class="text-end">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $transaction)
                @php
                    $order = $transaction->order;
                    $customer = $order?->customer;
                    $branch = $order?->branch;
                    $isPayment = $transaction->type === 'payment';
                @endphp
                <tr>
                    <td class="fw-bold text-start">{{ $transactions->firstItem() + $loop->index }}</td>
                    <td class="text-center">
                        <span class="badge bg-secondary fw-bold">#ORD-{{ $order?->id }}</span>
                    </td>
                    <td class="text-center">
                        <div class="fw-bold">{{ $customer?->name ?? 'N/A' }}</div>
                        <div class="small text-muted">{{ $customer?->phone ?? 'N/A' }}</div>
                    </td>
                    <td class="text-center">{{ $branch?->name ?? 'N/A' }}</td>
                    <td class="text-center">
                        @if ($isPayment)
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle"></i> Thanh toán
                            </span>
                        @else
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-undo"></i> Hoàn tiền
                            </span>
                        @endif
                    </td>
                    <td class="text-center" class="text-end fw-bold">
                        @if ($isPayment)
                            <span class="text-success">+{{ number_format($transaction->amount) }} đ</span>
                        @else
                            <span class="text-danger">-{{ number_format($transaction->amount) }} đ</span>
                        @endif
                    </td>
                    <td class="text-center">@php
                        $methods = [
                            'cash' => ['label' => 'Tiền mặt', 'class' => 'bg-success'],
                            'bank_transfer' => ['label' => 'Chuyển khoản', 'class' => 'bg-primary'],
                            'card' => ['label' => 'Thẻ', 'class' => 'bg-info'],
                        ];

                        $method = $methods[$transaction->payment_method] ?? [
                            'label' => ucfirst($transaction->payment_method),
                            'class' => 'bg-secondary',
                        ];
                    @endphp

                        <span class="badge {{ $method['class'] }}">
                            {{ $method['label'] }}
                        </span>
                    </td>
                    <td class="text-center">
                        <small>{{ $transaction->created_at ? \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i') : 'N/A' }}</small>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('payment.detail', ['id' => $order?->id]) }}"
                            class="btn btn-outline-primary btn-action-fixed" data-tooltip="Chi tiết">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">
                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                        Không có giao dịch nào
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@include('layouts.partials.pagination', ['items' => $transactions])
