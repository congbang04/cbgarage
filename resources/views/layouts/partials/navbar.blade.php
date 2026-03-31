<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#"><i class="fa-solid fa-gear"></i> CBAUTO</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="#">Trang chủ</a></li>
                <li class="nav-item"><a class="nav-link" href="#about">Về chúng tôi</a></li>
                <li class="nav-item"><a class="nav-link" href="#services">Dịch vụ</a></li>
                <li class="nav-item"><a class="nav-link" href="#pricing">Bảng giá</a></li>
                <li class="nav-item"><a class="nav-link" href="#reviews">Đánh giá</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('booking.index') }}">Đặt lịch</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('chat.index') }}">CBAUTO Chat</a></li>
            </ul>

            <div class="d-lg-none mt-3 pt-3 border-top text-center">
                @auth
                    <div class="fw-bold mb-2" style="color: var(--prime-dark);">
                        <i class="fas fa-user-circle me-1"></i> {{ optional(auth()->user())->customer->name ?? '-' }}
                    </div>
                    <a class="nav-link d-block py-2" href="{{ route('customer.profile') }}">Thông tin cá nhân</a>
                    <a class="nav-link d-block py-2 text-danger fw-bold" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); $('#logout-form').submit();">
                        Đăng xuất <i class="fas fa-sign-out-alt ms-1"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-brand w-100 mt-2">Đăng nhập</a>
                @endauth
            </div>
        </div>

        <div class="d-none d-lg-block">
            @auth
                <div class="btn-group">
                    <button type="button" class="btn btn-brand dropdown-toggle" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        {{ optional(auth()->user())->customer->name ?? '-' }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('customer.profile') }}">Thông tin cá nhân</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item text-danger" id="btn-logout" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); $('#logout-form').submit();">
                                Đăng xuất
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-brand">Đăng nhập</a>
            @endauth
        </div>
    </div>
</nav>
