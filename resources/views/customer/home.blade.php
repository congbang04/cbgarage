@extends('layouts.app')
@section('title', 'Trang chủ')
@section('vite')
    @vite(['resources/js/modules/chat-ai.js'])
@endsection
@section('styles')
    <link rel="stylesheet" href={{ asset('assets/css/design-system.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/customer.css') }}>
    <style>
        /* CSS cho khung chat nổi */
        .chat-widget-container {
            position: fixed;
            bottom: 80px;
            /* Cách đáy để không đè lên thanh nav ở mobile */
            right: 20px;
            width: 350px;
            max-height: 500px;
            background: white;
            z-index: 1050;
            display: flex;
            flex-direction: column;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            transition: all 0.3s ease;
            /* Ẩn mặc định */
            opacity: 0;
            pointer-events: none;
            transform: translateY(20px);
        }

        /* Lớp để hiển thị khung chat */
        .chat-widget-container.show {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0);
        }

        .chat-widget-messages {
            flex-grow: 1;
            overflow-y: auto;
            padding: 15px;
            height: 350px;
            background-color: #f8f9fa;
        }

        /* Định dạng tin nhắn */
        .chat-bubble {
            padding: 10px 15px;
            border-radius: 15px;
            margin-bottom: 10px;
            max-width: 85%;
            font-size: 0.9rem;
        }

        .chat-bubble.bot {
            background-color: #fff;
            border: 1px solid #e0e0e0;
            align-self: flex-start;
            border-bottom-left-radius: 5px;
        }

        .chat-bubble.user {
            background-color: #0d6efd;
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 5px;
            margin-left: auto;
        }
    </style>
@endsection

@section('header')
    @include('layouts.partials.navbar')
    @include('layouts.partials.header')
@endsection

@section('content')
    <!-- Main Content -->
    <main class="container">

        <!-- Section: Xe của tôi -->
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">Xe của tôi</h3>
                <a href="profile.html" class="text-primary text-decoration-none">Xem tất cả <i
                        class="fas fa-arrow-right small"></i></a>
            </div>

            <div class="row g-3">
                <!-- Car Card 1 -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="bg-light rounded p-3 me-3">
                                <i class="fas fa-car fa-2x text-secondary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-1">Toyota Vios</h5>
                                <div class="text-muted small mb-1">Biển số: <span
                                        class="fw-bold text-dark">51G-123.45</span></div>
                                <span class="badge bg-success-subtle text-success">Hoạt động</span>
                            </div>
                            <a href="car-detail.html" class="btn btn-outline-secondary btn-sm rounded-pill">Chi tiết</a>
                        </div>
                    </div>
                </div>
                <!-- Car Card 2 -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="bg-light rounded p-3 me-3">
                                <i class="fas fa-truck-pickup fa-2x text-secondary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-1">Ford Ranger</h5>
                                <div class="text-muted small mb-1">Biển số: <span
                                        class="fw-bold text-dark">59C-999.88</span></div>
                                <span class="badge bg-warning-subtle text-warning">Đến hạn bảo dưỡng</span>
                            </div>
                            <a href="car-detail.html" class="btn btn-outline-secondary btn-sm rounded-pill">Chi tiết</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section: Dịch vụ nổi bật -->
        <section>
            <h3 class="fw-bold mb-4">Dịch vụ nổi bật</h3>
            <div class="row g-3 row-cols-2 row-cols-md-4">
                <div class="col">
                    <div class="card service-card p-3 text-center h-100 border-0 shadow-sm">
                        <div class="service-icon"><i class="fas fa-oil-can"></i></div>
                        <h6 class="fw-bold">Thay dầu máy</h6>
                        <p class="text-muted small mb-0">Từ 450.000đ</p>
                    </div>
                </div>
                <div class="col">
                    <div class="card service-card p-3 text-center h-100 border-0 shadow-sm">
                        <div class="service-icon"><i class="fas fa-tools"></i></div>
                        <h6 class="fw-bold">Bảo dưỡng phanh</h6>
                        <p class="text-muted small mb-0">Từ 300.000đ</p>
                    </div>
                </div>
                <div class="col">
                    <div class="card service-card p-3 text-center h-100 border-0 shadow-sm">
                        <div class="service-icon"><i class="fas fa-spray-can"></i></div>
                        <h6 class="fw-bold">Đồng sơn</h6>
                        <p class="text-muted small mb-0">Liên hệ</p>
                    </div>
                </div>
                <div class="col">
                    <div class="card service-card p-3 text-center h-100 border-0 shadow-sm">
                        <div class="service-icon"><i class="fas fa-snowflake"></i></div>
                        <h6 class="fw-bold">Vệ sinh điều hòa</h6>
                        <p class="text-muted small mb-0">Từ 800.000đ</p>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Chat AI FAB -->
    <div id="chat-widget" class="chat-widget-container">
        <div class="bg-white shadow-sm px-3 py-2 d-flex align-items-center justify-content-between border-bottom">
            <div class="d-flex align-items-center">
                <div class="position-relative">
                    <img src="../assets/img/bot-avatar.png"
                        onerror="this.src='https:\\cdn-icons-png.flaticon.com/512/4712/4712027.png'"
                        class="rounded-circle border" width="40" height="40">
                    <span class="position-absolute bottom-0 end-0 bg-success p-1 rounded-circle border border-white"></span>
                </div>
                <div class="ms-2">
                    <div class="fw-bold small">Trợ lý ảo Garage</div>
                    <div class="text-success x-small" style="font-size: 10px;">Đang hoạt động</div>
                </div>
            </div>
            <div>
                <a href="{{ route('chat.index') }}" class="btn btn-light btn-sm rounded-circle">
                    <i class="fa-solid fa-up-right-and-down-left-from-center"></i>
                </a>

                <button id="close-chat" class="btn btn-light btn-sm rounded-circle">
                    <i class="fas fa-times"></i>
                </button>
            </div>

        </div>

        <div id="chat-messages" class="chat-widget-messages d-flex flex-column">
            <div class="text-center small text-muted my-3">Hôm nay</div>
            <div class="chat-bubble bot">
                Xin chào! 👋 <br> Em là trợ lý ảo của AutoCare. Em có thể giúp gì cho xe của anh/chị hôm nay?
            </div>
        </div>

        <div class="bg-white p-2 border-top">
            <div class="input-group">
                <input type="text" id="chat-input" class="form-control rounded-pill me-2"
                    placeholder="Nhập vấn đề của xe..." autocomplete="off">
                <button id="send-btn" class="btn btn-primary rounded-circle"><i class="fas fa-paper-plane"></i></button>
            </div>
        </div>
    </div>

    <button id="fab-chat-btn" class="fab-btn text-decoration-none">
        <i class="fas fa-robot"></i>
    </button>
    <!-- Navigation Bottom (Mobile Only) -->
    <nav class="navbar fixed-bottom navbar-light bg-white border-top d-md-none">
        <div class="container-fluid justify-content-around">
            <a href="home.html" class="text-center text-primary text-decoration-none">
                <i class="fas fa-home fs-5"></i>
                <div class="small" style="font-size: 10px;">Trang chủ</div>
            </a>
            <a href="booking.html" class="text-center text-secondary text-decoration-none">
                <i class="fas fa-calendar-plus fs-5"></i>
                <div class="small" style="font-size: 10px;">Đặt lịch</div>
            </a>
            <a href="profile.html" class="text-center text-secondary text-decoration-none">
                <i class="fas fa-user fs-5"></i>
                <div class="small" style="font-size: 10px;">Tài khoản</div>
            </a>
        </div>
    </nav>
@endsection
