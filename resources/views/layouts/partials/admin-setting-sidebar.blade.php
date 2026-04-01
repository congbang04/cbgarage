<nav class="sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="adminSidebar">
    <div class="sidebar-header d-flex align-items-center justify-content-between">
        <a href="{{ route('adminDashboard') }}" class="sidebar-brand fw-bold text-decoration-none">
            <i class="fas fa-wrench"></i>
            <span>CBAUTO</span>
        </a>
        <button type="button" class="btn-close btn-close-white d-lg-none" data-bs-dismiss="offcanvas"
            data-bs-target="#adminSidebar"></button>
    </div>


    <div class="sidebar-menu">
        <div class="menu-header">Cấu hình danh mục</div>

        <a href="{{ route('services.index') }}" class="nav-link @if (request()->routeIs('services.*')) active @endif">
            <i class="fas fa-tag"></i> <span>Dịch vụ & Giá</span>
        </a>

        <a class="nav-link {{ request()->routeIs('parts.index') ? 'active' : '' }}" href="{{ route('parts.index') }}">
            <i class="fas fa-box"></i> <span>Phụ tùng & Vật tư</span>
        </a>
        <a href="{{ route('branches.index') }}" class="nav-link @if (request()->routeIs('branches.*')) active @endif">
            <i class="fas fa-building"></i> <span>Chi nhánh</span>
        </a>
        <a href="{{ route('staff.index') }}" class="nav-link {{ request()->routeIs('staff.index') ? 'active' : '' }}">
            <i class="fas fa-users"></i> <span>Nhân sự</span>
        </a>

        <div class="menu-header">Tiện ích</div>
        <a href="{{ route('admin.ai-logs.index') }}" class="nav-link @if (request()->routeIs('admin.ai-logs.*')) active @endif">
            <i class="fas fa-robot"></i> <span>Quản trị AI</span>
        </a>

        <div class="mt-4 px-3">
            <a href="{{ route('adminDashboard') }}" class="btn btn-outline-secondary w-100 btn-sm text-start"
                style="color: #cbd5e1; border-color: #cbd5e1;">
                <i class="fas fa-arrow-left" style="margin-top: 4px"></i> Quay lại Vận hành
            </a>
        </div>
    </div>
</nav>
