@extends('layouts.admin')
@section('title', 'Thực thi công việc')
@section('vite')
    @vite(['resources/js/modules/technician-jobs/technician-jobs-execution.js'])
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
@endsection
@section('body-class', 'admin-wrapper')
@section('content')
    <div class="main-content">
        <header class="top-navbar">
            <div class="d-flex align-items-center">
                <a href="{{ route('technician.jobs') }}" class="btn btn-light me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h4 class="mb-0 fw-bold">
                        {{ $order->car->brand->name ?? '' }}
                        {{ $order->car->carModel->name ?? '' }}
                        - {{ $order->car->license_plate ?? '' }}
                    </h4>
                    @php
                        $statusBadge = match ($order->status) {
                            'IN_PROGRESS' => '<span class="status-badge info">Đang thực hiện</span>',
                            'WAITING_PART' => '<span class="status-badge warning">Chờ phụ tùng</span>',
                            'COMPLETED' => '<span class="status-badge success">Hoàn thành</span>',
                            default => '<span class="status-badge bg-secondary text-white">Chưa làm</span>',
                        };
                    @endphp
                    {!! $statusBadge !!}
                </div>
            </div>
        </header>

        <div class="p-4">
            <div class="row g-4">
                <!-- Left Column: Work Checklist & Parts -->
                <div class="col-lg-8">
                    <!-- Checklist -->
                    <div class="card mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">Checklist công việc</h5>
                        </div>
                        <div class="list-group list-group-flush" id="workChecklistContainer">
                            @forelse ($order->details as $index => $detail)
                                <div class="list-group-item p-3" data-detail-id="{{ $detail->id }}">
                                    <div class="d-flex align-items-center">
                                        <input class="form-check-input work-item-checkbox me-3 fs-4" type="checkbox"
                                            data-id="{{ $detail->id }}" {{ $index === 0 ? 'checked' : '' }}>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1">{{ $detail->item->name ?? 'Hạng mục không rõ' }}</h6>
                                            <small class="text-muted">
                                                Giá: {{ number_format($detail->price, 0, ',', '.') }} đ
                                                {{ $detail->quantity > 1 ? '× ' . $detail->quantity : '' }}
                                            </small>
                                        </div>
                                        @if ($index === 0)
                                            <span class="badge bg-success">Hoàn thành</span>
                                        @else
                                            <span class="badge bg-secondary">Chưa làm</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="list-group-item p-3 text-muted text-center">
                                    Không có dịch vụ trong công việc này
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Parts Used -->
                    <div class="card">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Vật tư sử dụng</h5>
                            <button class="btn btn-outline-primary btn-sm" id="requestPartBtn">
                                <i class="fas fa-plus"></i> Yêu cầu thêm
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Tên vật tư</th>
                                        <th>SKU</th>
                                        <th>SL Yêu cầu</th>
                                        <th>Giá</th>
                                        <th>Tổng</th>
                                    </tr>
                                </thead>
                                <tbody id="partsTableBody">
                                    @forelse ($order->partRequests as $partReq)
                                        @foreach ($partReq->parts as $part)
                                            <tr>
                                                <td class="fw-bold">{{ $part->name ?? 'Vật tư không rõ' }}</td>
                                                <td><code>{{ $part->sku ?? '-' }}</code></td>
                                                <td>{{ $part->pivot->quantity }}</td>
                                                <td>{{ number_format($part->price_sell ?? 0, 0, ',', '.') }} đ</td>
                                                <td>{{ number_format(($part->unit_price ?? 0) * $part->pivot->quantity, 0, ',', '.') }}
                                                    đ</td>
                                            </tr>
                                        @endforeach
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-muted text-center py-3">
                                                Chưa yêu cầu vật tư nào
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Status & Notes -->
                <div class="col-lg-4">
                    <!-- Status Update -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">Cập nhật tiến độ</h6>
                            <form id="statusUpdateForm">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="status" id="statusInProgress"
                                        value="IN_PROGRESS" {{ $order->status === 'IN_PROGRESS' ? 'checked' : '' }}>
                                    <label
                                        class="form-check-label {{ $order->status === 'IN_PROGRESS' ? 'fw-bold text-primary' : '' }}"
                                        for="statusInProgress">
                                        Đang thực hiện
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="status" id="statusWaitingPart"
                                        value="WAITING_PART" {{ $order->status === 'WAITING_PART' ? 'checked' : '' }}>
                                    <label
                                        class="form-check-label {{ $order->status === 'WAITING_PART' ? 'fw-bold text-warning' : '' }}"
                                        for="statusWaitingPart">
                                        Chờ phụ tùng
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="status" id="statusCompleted"
                                        value="COMPLETED" {{ $order->status === 'COMPLETED' ? 'checked' : '' }}>
                                    <label
                                        class="form-check-label {{ $order->status === 'COMPLETED' ? 'fw-bold text-success' : '' }}"
                                        for="statusCompleted">
                                        Hoàn thành
                                    </label>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Technical Notes -->
                    <div class="card">
                        <div class="card-body">
                            <label class="form-label fw-bold">Ghi chú kỹ thuật</label>
                            <textarea class="form-control mb-3" id="technicalNotes" rows="4"
                                placeholder="Ghi lại các lưu ý trong quá trình sửa...">{{ $order->note ?? '' }}</textarea>
                            <button class="btn btn-primary w-100 py-2" id="updateStatusBtn">
                                CẬP NHẬT TRẠNG THÁI
                            </button>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="card mt-3">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">Thông tin đơn hàng</h6>
                            <div class="small">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Khách hàng:</span>
                                    <span class="fw-bold">{{ $order->customer->name ?? '-' }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Điện thoại:</span>
                                    <span>{{ $order->customer->phone ?? '-' }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Mã phiếu:</span>
                                    <span
                                        class="fw-bold text-primary">ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Tổng tiền:</span>
                                    <span
                                        class="fw-bold text-primary">{{ number_format($order->total_amount, 0, ',', '.') }}
                                        đ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Request Part Modal -->
    @include('admin.technician.partials.request-part-modal', ['order' => $order])
@endsection
