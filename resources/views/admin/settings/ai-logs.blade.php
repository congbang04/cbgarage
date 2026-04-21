@extends('layouts.admin')

@section('title', 'CBAUTO - Nhật ký AI')

@section('vite')
    @vite(['resources/js/modules/chat-ai/admin-ai-logs.js'])
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    <style>
        .date-navigator {
            display: flex;
            align-items: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 4px;
        }

        .date-navigator input[type="date"] {
            border: none;
            background: transparent;
            font-weight: 600;
            color: var(--primary);
            text-align: center;
            outline: none;
            box-shadow: none;
            cursor: pointer;
            width: 140px;
        }

        .btn-date-nav {
            border: none;
            background: transparent;
            color: var(--secondary);
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .btn-date-nav:hover:not(:disabled) {
            background: #e2e8f0;
            color: var(--dark);
        }

        .btn-date-nav:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }
    </style>
@endsection

@section('body-class', 'admin-wrapper')

@section('sidebar')
    @include('layouts.partials.admin-setting-sidebar')
@endsection

@section('topbar_actions')
    <div class="date-navigator shadow-sm">
        <button id="btn-prev-day" class="btn-date-nav" title="Ngày hôm trước">
            <i class="fas fa-chevron-left"></i>
        </button>
        <input type="date" id="filter-date" title="Chọn ngày cụ thể">
        <button id="btn-next-day" class="btn-date-nav" title="Ngày hôm sau" disabled>
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
@endsection

@section('content')
    <div class="main-content">
        @include('layouts.partials.admin-top-navbar', [
            'headerTitle' => 'Giám sát hội thoại AI',
        ])

        <!-- Main Area -->
        <div class="p-4" style="height: calc(100vh - 80px);">
            <div class="row h-100 g-0 border rounded overflow-hidden bg-white shadow-sm">

                <!-- Left Column: Session List -->
                <div class="col-4 border-end h-100 d-flex flex-column">
                    <div class="p-3 border-bottom bg-light">
                        <input type="text" id="search-input" class="form-control form-control-sm"
                            placeholder="Tìm tên khách hoặc từ khóa...">
                    </div>
                    <div class="list-group list-group-flush flex-grow-1 overflow-auto" id="admin-session-list">
                        <div class="text-center p-4 text-muted"><i class="fas fa-spinner fa-spin"></i> Đang tải...</div>
                    </div>
                </div>

                <!-- Right Column: Chat Content -->
                <div class="col-8 h-100 d-flex flex-column">
                    <div class="p-3 border-bottom bg-light d-flex justify-content-between align-items-center">
                        <strong id="chat-header-title">Chọn một cuộc trò chuyện để xem</strong>
                        <button id="btn-delete-log" class="btn btn-sm btn-outline-danger d-none"><i
                                class="fas fa-trash"></i> Xóa log</button>
                    </div>

                    <div class="flex-grow-1 p-3 overflow-auto bg-light" id="admin-chat-content">
                        <div
                            class="h-100 d-flex flex-column align-items-center justify-content-center text-muted opacity-50">
                            <i class="fas fa-robot fa-4x mb-3"></i>
                            <h5>Chưa chọn phiên làm việc</h5>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
