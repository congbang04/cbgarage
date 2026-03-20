@extends('layouts.admin')
@section('title', 'Luân chuyển hàng hóa')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
@endsection
@section('body-class', 'admin-wrapper')
@section('sidebar')
    @include('layouts.partials.admin-sidebar')
@endsection
@section('content')
    <div class="main-content">
        <header class="top-navbar">
            <h4 class="mb-0 fw-bold">Luân chuyển hàng hóa</h4>
        </header>

        <div class="p-4">
            <div class="row g-4">
                <!-- Form tạo phiếu -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">Tạo phiếu yêu cầu nhập hàng (Từ Kho Tổng)</h5>
                        </div>
                        <div class="card-body">
                            <form id="transferForm">
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Kho nguồn (Xuất)</label>
                                        <select class="form-select" name="from_branch_id" required>
                                            <option value="">-- Chọn kho --</option>
                                            @foreach ($branches as $branch)
                                                @if ($branch->is_central_warehouse)
                                                    <option value="{{ $branch->id }}" selected>
                                                        {{ $branch->name }} (Kho Tổng)
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Kho đích (Nhập)</label>
                                        <select class="form-select" name="to_branch_id" required>
                                            <option value="">-- Chọn chi nhánh --</option>
                                            @foreach ($branches as $branch)
                                                @unless ($branch->is_central_warehouse)
                                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                @endunless
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="table-responsive mb-3">
                                    <table class="table table-bordered align-middle">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Mã / Tên phụ tùng</th>
                                                <th style="width: 150px;">SL Yêu cầu</th>
                                                <th style="width: 50px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="transferItems">
                                            <tr class="transfer-item">
                                                <td>
                                                    <select class="form-select border-0 part-select"
                                                        name="items[0][part_id]" required>
                                                        <option value="">-- Chọn phụ tùng --</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" name="items[0][quantity]"
                                                        min="1" required>
                                                </td>
                                                <td><button type="button" class="btn btn-sm text-danger remove-row">
                                                        <i class="fas fa-trash"></i>
                                                    </button></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-center">
                                                    <button type="button" class="btn btn-sm btn-outline-primary w-100"
                                                        id="addRowBtn">
                                                        <i class="fas fa-plus"></i> Thêm dòng
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Ghi chú</label>
                                    <textarea class="form-control" name="note" rows="2" placeholder="VD: Cần gấp cho xe đang nằm xưởng..."></textarea>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i> Gửi yêu cầu
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Lịch sử phiếu -->
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">Lịch sử phiếu</h5>
                        </div>
                        <div class="list-group list-group-flush" id="transferHistory">
                            @forelse($transfers as $transfer)
                                <a href="#" class="list-group-item list-group-item-action py-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong
                                            class="text-primary">#TRF-{{ str_pad($transfer->id, 3, '0', STR_PAD_LEFT) }}</strong>
                                        <span
                                            class="badge
                                            @if ($transfer->status === 'pending') bg-warning text-dark
                                            @elseif ($transfer->status === 'in_transit') bg-info text-white
                                            @else bg-success-subtle text-success @endif">
                                            @if ($transfer->status === 'pending')
                                                Chờ gửi
                                            @elseif ($transfer->status === 'in_transit')
                                                Đang vận chuyển
                                            @else
                                                Hoàn thành
                                            @endif
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    @push('scripts')
        <script type="module" src="{{ asset('js/modules/warehouse/stock-transfer.js') }}"></script>
    @endpush
@endsection
