    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="home.html">
                <i class="fas fa-car-side me-2"></i>GARAGE PRO
            </a>
            {{-- <div class="d-flex align-items-center gap-3">
                <a href="profile.html" class="d-flex align-items-center text-dark text-decoration-none">
                    <img src="https://ui-avatars.com/api/?name=Nguyen+Van+A&background=random" class="rounded-circle me-2"
                        width="32" height="32" alt="Avatar">
                    <span class="d-none d-md-block fw-500">Nguyễn Văn A</span>
                </a>
            </div> --}}

            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Nguyễn Văn A
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Action</a></li>
                    <li><a class="dropdown-item" href="#">Another action</a></li>
                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item" id="btn-logout" href="{{ route('auth.logout') }}"
                            onclick="event.preventDefault(); $('#logout-form').submit();">
                            Đăng xuất
                        </a>
                        <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
