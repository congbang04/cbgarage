<header class="top-navbar">
    <div class="d-flex align-items-center gap-3">

        <button class="btn btn-light d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar"
            aria-controls="adminSidebar">
            <i class="fas fa-bars"></i>
        </button>


        @if (isset($headerTitle))
            <h4 class="mb-0 fw-bold d-none d-md-block">{{ $headerTitle }}</h4>
        @endif
    </div>

    <div class="d-flex align-items-center gap-4">
        @yield('topbar_actions')

        <div class="user-dropdown">
            <div class="text-end d-none d-md-block">
                <div class="fw-bold text-dark">{{ auth()->user()->email }}</div>
                <small class="text-muted">{{ auth()->user()->branch->name }}</small>
            </div>
            <img src="https://ui-avatars.com/api/?name=Admin+User&background=0D8ABC&color=fff" class="user-avatar"
                alt="Admin">
        </div>
    </div>
</header>
