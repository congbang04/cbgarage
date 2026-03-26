@extends('layouts.app')

@section('title', 'Chat hỗ trợ | Garage Pro')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/customer.css') }}">
    <style>
        /* CSS bổ sung để full trang chat hiển thị đẹp hơn trong layout main */
        .chat-container-full {
            height: calc(100vh - 160px);
            display: flex;
            flex-direction: column;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
    </style>
@endsection

@section('vite')
    @vite(['resources/js/modules/chat-ai.js'])
@endsection

@section('header')
    <!-- Bạn có thể include header chung ở đây nếu muốn -->
    <div class="bg-white shadow-sm px-3 py-2 d-flex align-items-center justify-content-between sticky-top">
        <div class="d-flex align-items-center">
            <a href="{{ url('/') }}" class="text-dark me-3"><i class="fas fa-arrow-left"></i></a>
            <div class="position-relative">
                <img src="{{ asset('assets/img/bot-avatar.png') }}" class="rounded-circle border" width="40"
                    height="40">
                <span class="position-absolute bottom-0 end-0 bg-success p-1 rounded-circle border border-white"></span>
            </div>
            <div class="ms-2">
                <div class="fw-bold small">Trợ lý ảo Garage</div>
                <div class="text-success x-small" style="font-size: 10px;">Đang hoạt động</div>
            </div>
        </div>
        <button class="btn btn-light btn-sm rounded-circle"><i class="fas fa-phone"></i></button>
    </div>
@endsection

@section('content')
    <div class="container mt-3 mb-5">
        <div class="chat-container-full">
            <!-- Khung chứa tin nhắn -->
            <div class="chat-messages" id="full-chat-messages">
                <div class="text-center small text-muted my-3">Hôm nay</div>

                <div class="chat-bubble bot">
                    Xin chào! 👋 <br>
                    Em có thể giúp gì cho chiếc xe của anh/chị hôm nay?
                </div>
            </div>

            <!-- Input Area -->
            <div class="chat-input-area border-top">
                <div class="input-group p-2">
                    <button class="btn btn-light text-secondary border"><i class="fas fa-camera"></i></button>
                    <input type="text" id="full-chat-input" class="form-control border-start-0 border-end-0"
                        placeholder="Nhập tin nhắn..." autofocus autocomplete="off">
                    <button id="full-send-btn" class="btn btn-primary"><i class="fas fa-paper-plane"></i></button>
                </div>
            </div>
        </div>
    </div>
@endsection
