<div class="row g-4 mb-4" id="stats-cards">
    <div class="col-md-3">
        <div class="card p-3 border-start border-4 border-success shadow p-3 mb-5 bg-body-tertiary rounded"
            style="height: 102px">
            <div class="text-muted small">Tổng thanh toán</div>
            <h4 class="fw-bold mb-0 text-success">
                {{ number_format($stats['total_payment'] ?? 0) }} đ
            </h4>
            <small class="text-muted">{{ $stats['payment_count'] ?? 0 }} giao dịch</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 border-start border-4 border-warning shadow p-3 mb-5 bg-body-tertiary rounded"
            style="height: 102px">
            <div class="text-muted small">Tổng hoàn tiền</div>
            <h4 class="fw-bold mb-0 text-warning">
                {{ number_format($stats['total_refund'] ?? 0) }} đ
            </h4>
            <small class="text-muted">{{ $stats['refund_count'] ?? 0 }} giao dịch</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 border-start border-4 border-primary shadow p-3 mb-5 bg-body-tertiary rounded"
            style="height: 102px">
            <div class="text-muted small">Tiền thực nhận</div>
            <h4 class="fw-bold mb-0 text-primary">
                {{ number_format($stats['net_revenue'] ?? 0) }} đ
            </h4>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 border-start border-4 border-info shadow p-3 mb-5 bg-body-tertiary rounded"
            style="height: 102px">
            <div class="text-muted small">Tỷ suất hoàn tiền</div>
            <h4 class="fw-bold mb-0 text-info">
                @php
                    $totalPayment = $stats['total_payment'] ?? 0;
                    $totalRefund = $stats['total_refund'] ?? 0;
                    $rate = $totalPayment > 0 ? round(($totalRefund / $totalPayment) * 100, 2) : 0;
                @endphp
                {{ $rate }}%
            </h4>
        </div>
    </div>
</div>
