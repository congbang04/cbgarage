@extends('layouts.admin')
@section('title', 'Phiếu xuất kho')
@section('vite')
    @vite(['resources/js/modules/warehouse/goods-issue.js'])
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    <style>
        .invoice-paper {
            background: #fff;
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .invoice-paper,
            .invoice-paper * {
                visibility: visible;
            }

            .invoice-paper {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none;
                padding: 0;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
@endsection
@section('body-class', 'admin-wrapper')
@section('sidebar')
    @include('layouts.partials.admin-sidebar')
@endsection
@section('content')
    <div class="main-content">
        <header class="top-navbar no-print">
            <div class="d-flex align-items-center">
                <a href="{{ route('warehouse.part-requests') }}" class="btn btn-light me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h4 class="mb-0 fw-bold">Chi tiết Phiếu xuất kho</h4>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary no-print" onclick="window.print()">
                    <i class="fas fa-print"></i> In phiếu
                </button>
                @if ($stockTransfer->status === 'PENDING')
                    <button class="btn btn-success no-print" id="confirmIssueBtn"
                        data-transfer-request-id="{{ $stockTransfer->id }}">
                        <i class="fas fa-check"></i> Xác nhận xuất
                    </button>
                @endif
            </div>
        </header>

        <div class="p-4">
            <div class="invoice-paper">
                <!-- Header Phiếu -->
                <div class="row mb-4 border-bottom pb-3">
                    <div class="col-6">
                        <h4 class="fw-bold text-uppercase">Garage Pro System</h4>
                        <div class="small">
                            Kho: <strong>Chi nhánh {{ optional($stockTransfer->fromBranch)->name ?? 'N/A' }}</strong>
                        </div>
                        <div class="small">Địa chỉ: {{ optional($stockTransfer->fromBranch)->address ?? 'N/A' }}</div>
                    </div>
                    <div class="col-6 text-end">
                        <h3 class="fw-bold text-dark">PHIẾU XUẤT KHO</h3>
                        <div class="text-muted">Số: <strong>PXK-{{ $stockTransfer->id }}</strong></div>
                        <div class="text-muted">Ngày: {{ $stockTransfer->created_at ?? 'N/A' }}</div>
                    </div>
                </div>

                <!-- Thông tin Người nhận -->
                <div class="row mb-4">
                    <div class="col-12">
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <td style="width: 150px;" class="text-secondary">Bên yêu cầu:</td>
                                <td class="fw-bold">Chi nhánh {{ $stockTransfer->toBranch->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="text-secondary">Lý do xuất:</td>
                                <td>Bổ sung vật tự cho chi nhánh <br>
                                    <strong>{{ $stockTransfer->toBranch->name ?? 'N/A' }}</strong><br>
                                    (Phiếu #ORD-{{ str_pad($stockTransfer->id, 5, '0', STR_PAD_LEFT) }})
                                </td>
                            </tr>
                            <tr>
                                <td class="text-secondary">Từ:</td>
                                <td class="fst-italic">{{ $stockTransfer->fromBranch->name ?? 'N/A' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-secondary">Đến:</td>
                                <td class="fst-italic">
                                    {{ $stockTransfer->toBranch->name ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Bảng Vật tư -->
                <table class="table table-bordered mb-5">
                    <thead class="bg-light text-uppercase small">
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th>Mã VTTB</th>
                            <th>Tên Vật tư / Phụ tùng</th>
                            <th class="text-center">ĐVT</th>
                            <th class="text-center">SL Yêu cầu</th>
                            <th class="text-center">Thực xuất</th>
                            <th>Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stockTransfer->details as $key => $detail)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ $detail->part->sku }}</td>
                                <td>{{ $detail->part->name }}</td>
                                <td class="text-center">{{ $detail->part->unit }}</td>
                                <td class="text-center">{{ $detail->quantity }}</td>
                                <td class="text-center fw-bold">
                                    <input type="number" class="form-control form-control-sm text-center actual-qty"
                                        data-detail-id="{{ $detail->id }}" value="{{ $detail->quantity }}"
                                        min="0" {{ $stockTransfer->status !== 'pending' ? 'disabled' : '' }}
                                        style="width: 80px;">
                                </td>
                                <td></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Không có vật tư</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Chữ ký -->
                <div class="row text-center mt-5">
                    <div class="col-4">
                        <div class="fw-bold mb-5">Người lập phiếu</div>
                        <div>(Ký, họ tên)</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold mb-5">Người nhận hàng</div>
                        <div>(Ký, họ tên)</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold mb-5">Thủ kho</div>
                        <div>(Ký, họ tên)</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
