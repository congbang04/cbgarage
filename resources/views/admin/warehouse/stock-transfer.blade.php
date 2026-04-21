@extends('layouts.admin')
@section('title', 'CBAUTO - Luân chuyển')
@section('vite')
    @vite(['resources/js/modules/warehouse/stock-transfer.js'])
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    <style>
        /* Tùy chỉnh thanh cuộn cho danh sách lịch sử */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            /* Thanh cuộn mỏng */
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
@endsection
@section('body-class', 'admin-wrapper')
@section('sidebar')
    @include('layouts.partials.admin-sidebar')
@endsection
@section('content')
    <div class="main-content">
        @include('layouts.partials.admin-top-navbar', [
            'headerTitle' => 'Luân chuyển hàng hóa',
        ])

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
                                                @unless ($branch->id === auth()->user()->branch_id)
                                                    <option value="{{ $branch->id }}">
                                                        {{ $branch->name }}
                                                    </option>
                                                @endunless
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Kho đích (Nhập)</label>
                                        <select class="form-select" name="to_branch_id" required>
                                            <option value="">-- Chọn chi nhánh --</option>
                                            @foreach ($branches as $branch)
                                                @if ($branch->id === auth()->user()->branch_id)
                                                    <option value="{{ $branch->id }}"selected>
                                                        {{ $branch->name }}</option>
                                                @endif
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
                                                        min="1" value="1">
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
                <div class="col-lg-4" id="transfer-history">
                    {{-- ajax render --}}
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
