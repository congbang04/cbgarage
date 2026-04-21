@extends('layouts.admin')
@section('title', 'CBAUTO - Lịch sử luân chuyển')
@section('vite')
    @vite(['resources/js/modules/warehouse/stock-transfer.js'])
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
@endsection
@section('body-class', 'admin-wrapper')
@section('sidebar')
    @include('layouts.partials.admin-sidebar')
@endsection
@section('topbar_actions')
    <a href="{{ route('warehouse.stock-transfer') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Luân chuyển
    </a>
@endsection
@section('content')
    <div class="main-content">
        @include('layouts.partials.admin-top-navbar', [
            'headerTitle' => 'Lịch sử luân chuyển kho',
        ])

        <div class="p-4">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="statusFilter" class="form-label">Tìm kiếm</label>
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" class="form-control" name="keyword" id="searchInput"
                                    placeholder="Tìm theo mã phiếu (VD: TRF-001)...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="statusFilter" class="form-label">Trạng thái</label>
                            <select class="form-select" name="status" id="statusFilter">
                                <option value="">Tất cả trạng thái</option>
                                <option value="PENDING">Chờ duyệt / Chờ gửi</option>
                                <option value="IN_TRANSIT">Đang vận chuyển</option>
                                <option value="COMPLETED">Đã hoàn thành</option>
                                <option value="CANCELED">Đã hủy</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="dateFromFilter" class="form-label">Từ ngày</label>
                            <input type="date" class="form-control" id="dateFromFilter" name="date_from">
                        </div>
                        <div class="col-md-3">
                            <label for="dateToFilter" class="form-label">Đến ngày</label>
                            <input type="date" class="form-control" id="dateToFilter" name="date_to"
                                value="{{ now() }}">
                        </div>
                    </div>
                </div>
            </div>

            <div id="table-data">
                {{-- ajax render --}}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Hàm load dữ liệu qua AJAX
            function loadTransfers(url) {
                const formData = {
                    keyword: $('#keyword').val(),
                    status: $('#status').val(),
                    date: $('#date').val()
                };

                $.ajax({
                    url: url || '{{ route('warehouse.transfer.history') }}',
                    type: 'GET',
                    data: formData,
                    beforeSend: function() {
                        $('#table-data').css('opacity', '0.5'); // Hiệu ứng mờ khi load
                    },
                    success: function(response) {
                        $('#table-data').html(response).css('opacity', '1');
                    },
                    error: function() {
                        alert('Có lỗi xảy ra khi tải dữ liệu.');
                        $('#table-data').css('opacity', '1');
                    }
                });
            }

            // Sự kiện khi submit form lọc
            $('#filterForm').on('submit', function(e) {
                e.preventDefault();
                loadTransfers();
            });

            // Sự kiện khi bấm nút reset
            $('#filterForm').on('reset', function() {
                setTimeout(() => loadTransfers(), 10);
            });

            // Sự kiện click phân trang (Pagination)
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                let url = $(this).attr('href');
                loadTransfers(url);
            });
        });
    </script>
@endsection
