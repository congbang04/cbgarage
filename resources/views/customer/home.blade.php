@extends('layouts.app')
@section('title', 'Trang chủ')
@section('vite')
    @vite(['resources/js/modules/chat-ai/chat-ai-widget.js'])
@endsection
@section('styles')
    <link rel="stylesheet" href={{ asset('assets/css/home.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/chat-ai-widget.css') }}>
@endsection
@section('header')
    @include('layouts.partials.navbar')
    @include('layouts.partials.header')
@endsection

@section('content')
    <section class="py-5" style="margin-top: 20px; position: relative; z-index: 2;">
        <div class="container">
            <div class="section-title mb-4" data-aos="fade-up">
                <h2>Kinh Nghiệm Thực Tế, Kết Quả Thực Tế</h2>
                <p>Gara của chúng tôi được hàng trăm tài xế tin tưởng mỗi năm. Dưới đây là những gì chúng tôi đã đạt
                    được:</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="brand-card">
                        <h4>15+ Năm<br>Kinh Nghiệm</h4>
                        <p>Phục vụ cộng đồng với dịch vụ chăm sóc xe hơi chuyên nghiệp.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="brand-card">
                        <h4>2,500+ Xe<br>Đã Sửa Chữa</h4>
                        <p>Từ những lỗi nhỏ nhất đến đại tu toàn bộ động cơ.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="brand-card">
                        <h4>98% Khách Hàng<br>Hài Lòng</h4>
                        <p>Tài xế tin tưởng giao phó sự an toàn của họ cho chúng tôi.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="brand-card">
                        <h4>Hoàn Thành<br>Trong 24 Giờ</h4>
                        <p>Dịch vụ nhanh chóng đối với hầu hết các sửa chữa tiêu chuẩn.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="about-section">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Gara Ô Tô Uy Tín Của Bạn</h2>
                <p>Với nhiều năm kinh nghiệm, đội ngũ thợ máy đạt chuẩn cung cấp dịch vụ sửa chữa trung thực, giá cả
                    phải chăng và chất lượng cao.</p>
                <a href="{{ route('booking.index') }}" class="btn btn-brand mt-3">
                    <i class="far fa-calendar-check me-2"></i>
                    ĐẶT LỊCH NGAY
                </a>
            </div>
            <div class="row align-items-center mt-5">
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                    <img src="https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?auto=format&fit=crop&w=800&q=80"
                        alt="Tools" class="img-fluid rounded shadow">
                </div>
                <div class="col-lg-6 ps-lg-5" data-aos="fade-left">
                    <p class="mb-4">Tại Prime Auto, chúng tôi tin tưởng vào dịch vụ minh bạch và chăm sóc khách hàng
                        dài hạn. Từ việc thay dầu đơn giản đến sửa chữa động cơ phức tạp, đội ngũ của chúng tôi tận tâm
                        giữ cho phương tiện của bạn an toàn và bền bỉ trên đường.</p>
                    <div class="row text-center mt-4">
                        <div class="col-4">
                            <i class="fa-solid fa-award feature-icon"></i>
                            <h6 class="fw-bold">Thợ Máy<br>Đạt Chuẩn</h6>
                        </div>
                        <div class="col-4">
                            <i class="fa-solid fa-shield-halved feature-icon"></i>
                            <h6 class="fw-bold">Bảo Hành<br>Sửa Chữa</h6>
                        </div>
                        <div class="col-4">
                            <i class="fa-solid fa-tags feature-icon"></i>
                            <h6 class="fw-bold">Giá Cả<br>Hợp Lý</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="py-5 my-5">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Bảo Dưỡng & Sửa Chữa Ô Tô Toàn Diện</h2>
                <p>Từ kiểm tra nhanh đến sửa chữa lớn, chúng tôi hỗ trợ mọi bộ phận trên xe của bạn.</p>
                <a href="{{ route('booking.index') }}" class="btn btn-brand mt-3">
                    <i class="far fa-calendar-check me-2"></i>
                    ĐẶT LỊCH NGAY
                </a>
            </div>

            <div class="row g-4 mt-4">
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="brand-card">
                        <h4>Chẩn Đoán &<br>Sửa Động Cơ</h4>
                        <p>Công cụ hiện đại và thợ tay nghề cao giải quyết lỗi động cơ nhanh chóng.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="brand-card">
                        <h4>Dịch Vụ Phanh &<br>Thay Thế</h4>
                        <p>Giữ an toàn với quy trình kiểm tra, sửa chữa và thay thế phanh chính xác.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="brand-card">
                        <h4>Kiểm Tra &<br>Thay Ắc Quy</h4>
                        <p>Kiểm tra nhanh và cung cấp ắc quy chuẩn để xe khởi động mạnh mẽ.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="brand-card">
                        <h4>Dịch Vụ Lốp &<br>Cân Bằng Động</h4>
                        <p>Lái êm ái, kéo dài tuổi thọ lốp với dịch vụ cân chỉnh chuyên gia.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="brand-card">
                        <h4>Thay Dầu &<br>Bảo Dưỡng Định Kỳ</h4>
                        <p>Mức giá hợp lý giúp động cơ sạch sẽ, hiệu quả và bền bỉ hơn.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="brand-card">
                        <h4>Dịch Vụ<br>Hộp Số</h4>
                        <p>Sửa chữa và bảo dưỡng chuyên nghiệp đảm bảo sang số mượt mà.</p>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5" data-aos="fade-up">
                <p class="text-muted small">Đội ngũ của chúng tôi cung cấp đầy đủ dịch vụ để xe bạn luôn an toàn. Dù là
                    thay dầu hay đại tu động cơ, chúng tôi luôn có giải pháp tối ưu với chi phí hợp lý nhất.</p>
            </div>
        </div>
    </section>

    <section id="pricing" class="about-section">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Bảng Giá Minh Bạch & Hợp Lý</h2>
                <p>Báo giá rõ ràng, không có chi phí ẩn — bạn luôn biết mình đang trả tiền cho điều gì.</p>
                <a href="{{ route('booking.index') }}" class="btn btn-brand mt-3">
                    <i class="far fa-calendar-check me-2"></i>
                    ĐẶT LỊCH NGAY
                </a>
            </div>

            <div class="row mt-5">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="accordion" id="pricingAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne">
                                    Thay Dầu Máy
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show"
                                data-bs-parent="#pricingAccordion">
                                <div class="accordion-body">
                                    <span class="price-tag">từ 490.000đ</span>
                                    Dịch vụ nhanh chóng, chuyên nghiệp giúp động cơ luôn khỏe mạnh.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo">
                                    Thay Má Phanh
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#pricingAccordion">
                                <div class="accordion-body">
                                    <span class="price-tag">từ 850.000đ</span>
                                    Đảm bảo an toàn tối đa với các loại má phanh chất lượng cao.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseThree">
                                    Thay Bình Ắc Quy
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse"
                                data-bs-parent="#pricingAccordion">
                                <div class="accordion-body">
                                    <span class="price-tag">từ 1.200.000đ</span>
                                    Cung cấp năng lượng ổn định với các thương hiệu ắc quy hàng đầu.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFour">
                                    Chẩn Đoán Tổng Thể
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse"
                                data-bs-parent="#pricingAccordion">
                                <div class="accordion-body">
                                    <span class="price-tag">từ 600.000đ</span>
                                    Sử dụng máy tính hiện đại để phát hiện lỗi trước khi chúng nghiêm trọng hơn.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button">Đảo
                                    Lốp & Cân Bằng</button></h2>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button">Cân
                                    Chỉnh Thước Lái</button></h2>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button">Bảo
                                    Dưỡng Điều Hòa</button></h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-4 mt-lg-0" data-aos="fade-left">
                    <img src="{{ asset('storage/home/section-price.png') }}" alt="Mechanic working"
                        class="img-fluid rounded" style="object-fit: cover; height: 100%; min-height: 400px;">
                </div>
            </div>
        </div>
    </section>

    <section id="reviews" class="py-5 my-5">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Khách Hàng Nói Gì Về Chúng Tôi</h2>
                <p>Gara của chúng tôi được hàng ngàn tài xế tin tưởng. Dưới đây là cảm nhận của họ:</p>
            </div>

            <div class="row g-3">
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="100">
                    <div class="review-card">
                        <div class="stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i>
                        </div>
                        <p class="review-text">"Nhanh chóng, trung thực và giá tốt - gara ruột của tôi suốt nhiều năm
                            qua."</p>
                        <p class="review-author">— Minh T.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="200">
                    <div class="review-card">
                        <div class="stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i>
                        </div>
                        <p class="review-text">"Họ giải thích mọi thứ rất rõ ràng và hoàn thành sửa chữa trong ngày.
                            Rất đáng giới thiệu!"</p>
                        <p class="review-author">— Lan Anh S.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="300">
                    <div class="review-card">
                        <div class="stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i>
                        </div>
                        <p class="review-text">"Cuối cùng cũng tìm được thợ sửa xe có tâm. Dịch vụ tuyệt vời và giá cả
                            công bằng."</p>
                        <p class="review-author">— Hoàng R.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="400">
                    <div class="review-card">
                        <div class="stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i>
                        </div>
                        <p class="review-text">"Chuyên nghiệp, thân thiện và đáng tin cậy. Xe của tôi chưa bao giờ chạy
                            tốt thế này."</p>
                        <p class="review-author">— Ngọc Tr.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div style="height: 300px; width: 100%; background: #ddd;">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.123456789!2d106.6234567!3d10.8234567!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTDCsDQ5JzI0LjQiTiAxMDbCsDM3JzI0LjQiRQ!5e0!3m2!1svi!2s!4v1620000000000!5m2!1svi!2s"
            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    </div>
    <!-- Chat AI FAB -->
    <div id="chat-widget" class="chat-widget-container">
        <div class="bg-white shadow-sm px-3 py-2 d-flex align-items-center justify-content-between border-bottom">
            <div class="d-flex align-items-center">
                <div class="position-relative">
                    <img src="https:\\cdn-icons-png.flaticon.com/512/4712/4712027.png" class="rounded-circle border"
                        width="40" height="40">
                    <span
                        class="position-absolute bottom-0 end-0 bg-success p-1 rounded-circle border border-white"></span>
                </div>
                <div class="ms-2">
                    <div class="fw-bold small">Trợ lý ảo CBAUTO</div>
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
@endsection
@section('footer')
    @include('layouts.partials.footer')
@endsection
