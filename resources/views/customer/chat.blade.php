@extends('layouts.app')

@section('title', 'CBAUTO - Trợ lý AI')

@section('vite')
    @vite(['resources/js/modules/chat-ai/chat-ai.js'])
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/chat-ai.css') }}">
@endsection

@section('content')
    <div class="gemini-layout">

        <!-- SIDEBAR -->
        <aside class="gemini-sidebar" id="gemini-sidebar">
            <div class="sidebar-header-top">
                <button class="menu-toggle-btn" id="btn-toggle-sidebar" title="Thu gọn menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <button class="btn-new-chat" id="btn-new-chat">
                <i class="fas fa-plus"></i>
                <span>Trò chuyện mới</span>
            </button>

            <div class="recent-title">Gần đây</div>

            <!-- Danh sách chat từ JS sẽ render vào đây -->
            <div class="session-list" id="session-list">
                <div class="text-center text-muted mt-3 small">
                    <i class="fas fa-circle-notch fa-spin"></i> Đang tải...
                </div>
            </div>
        </aside>

        <!-- MAIN CHAT AREA -->
        <main class="gemini-main">
            <!-- Header -->
            <header class="main-header">
                <div class="main-header-left">
                    <button class="menu-toggle-btn d-md-none" id="btn-mobile-menu">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="model-selector">
                        <a class="navbar-brand" href="{{ url('/') }}">
                            <img src="{{ asset('storage/logo/logo-full.png') }}" alt="CBAUTO" class="logo-full-img">
                        </a>
                        <span>| CBAUTO AI</span>
                    </div>
                </div>

                <div class="main-header-right d-flex align-items-center gap-3">
                    <a href="{{ url('/') }}" class="text-secondary text-decoration-none" title="Trở về trang chủ">
                        <i class="fas fa-home fs-5"></i>
                    </a>
                </div>
            </header>

            <!-- Messages Container -->
            <div class="chat-messages-container" id="chat-scroll-area">

                <div class="chat-messages-wrapper" id="full-chat-messages">

                    <!-- Màn hình chào mừng (Sẽ bị JS xóa/ẩn khi có tin nhắn) -->
                    <div class="welcome-screen">
                        <div class="hello-text">Xin chào, Anh/Chị</div>
                        <div class="hello-sub">Hôm nay em có thể giúp gì cho xe của mình?</div>

                        <div class="suggestion-cards mt-5">
                            <div class="sugg-card"
                                onclick="document.getElementById('full-chat-input').value='Xe tôi bị kêu két két khi phanh'; document.getElementById('full-chat-input').focus();">
                                <span class="text-muted small fw-medium">Phân tích tiếng kêu</span>
                                <p class="mt-1 small">khi đạp phanh gấp</p>
                                <i class="fas fa-car-crash"></i>
                            </div>
                            <div class="sugg-card"
                                onclick="document.getElementById('full-chat-input').value='Báo giá bảo dưỡng xe mốc 4 vạn'; document.getElementById('full-chat-input').focus();">
                                <span class="text-muted small fw-medium">Tư vấn bảo dưỡng</span>
                                <p class="mt-1 small">cho các mốc định kỳ</p>
                                <i class="fas fa-tools"></i>
                            </div>
                            <div class="sugg-card"
                                onclick="document.getElementById('full-chat-input').value='Đèn check engine sáng màu vàng'; document.getElementById('full-chat-input').focus();">
                                <span class="text-muted small fw-medium">Chẩn đoán lỗi</span>
                                <p class="mt-1 small">khi xe báo đèn taplo</p>
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="sugg-card"
                                onclick="document.getElementById('full-chat-input').value='Tôi muốn đặt lịch kiểm tra xe ngày mai'; document.getElementById('full-chat-input').focus();">
                                <span class="text-muted small fw-medium">Hỗ trợ đặt lịch</span>
                                <p class="mt-1 small">kiểm tra nhanh chóng</p>
                                <i class="far fa-calendar-check"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Input Area -->
            <div class="gemini-input-container">
                <div class="gemini-input-box shadow-sm border">
                    <button class="icon-btn-circle" title="Tải ảnh lên (Sắp ra mắt)">
                        <i class="fas fa-plus"></i>
                    </button>
                    <input type="text" id="full-chat-input" placeholder="Nhập câu hỏi tại đây..." autocomplete="off">
                    <button class="icon-btn-circle send-btn" id="full-send-btn" disabled>
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
                <div class="disclaimer-text">
                    CBAUTO AI có thể đưa ra chẩn đoán không chính xác tuyệt đối. Vui lòng mang xe đến trung tâm để được kỹ
                    thuật viên kiểm tra thực tế.
                </div>
            </div>
        </main>
    </div>

    <!-- Script xử lý UI nội bộ -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle Sidebar Desktop
            const btnToggle = document.getElementById('btn-toggle-sidebar');
            const sidebar = document.getElementById('gemini-sidebar');

            if (btnToggle && sidebar) {
                btnToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                });
            }

            // Toggle Sidebar Mobile
            const btnMobileMenu = document.getElementById('btn-mobile-menu');
            if (btnMobileMenu && sidebar) {
                btnMobileMenu.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }

            // Kích hoạt nút Send khi có text
            const input = document.getElementById('full-chat-input');
            const sendBtn = document.getElementById('full-send-btn');

            if (input && sendBtn) {
                input.addEventListener('input', function() {
                    if (this.value.trim().length > 0) {
                        sendBtn.classList.add('active');
                        sendBtn.removeAttribute('disabled');
                    } else {
                        sendBtn.classList.remove('active');
                        sendBtn.setAttribute('disabled', 'true');
                    }
                });
            }
        });
    </script>
@endsection
