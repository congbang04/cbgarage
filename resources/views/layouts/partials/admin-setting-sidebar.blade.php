<nav class="sidebar">
    <div class="sidebar-header">
        <a href="#" class="sidebar-brand"><i class="fas fa-wrench text-primary"></i> GARAGE PRO</a>
    </div>
    <div class="sidebar-menu">
        <a href="{{ route('services.index') }}" class="nav-link @if (request()->routeIs('services.index')) active @endif"><i
                class="fas fa-tag"></i> Dịch vụ & Giá</a>
        <a href="{{ route('parts.index') }}" class="nav-link @if (request()->routeIs('parts.index')) active @endif"><i
                class="fas fa-box"></i> Phụ tùng & Vật tư</a>
        <a href="{{ route('branches.index') }}" class="nav-link @if (request()->routeIs('branches.index')) active @endif"><i
                class="fas fa-building"></i> Chi nhánh</a>
        <a href="{{ route('staff.index') }}" class="nav-link @if (request()->routeIs('staff.index')) active @endif"><i
                class="fas fa-users"></i> Nhân sự</a>
        <a href="#" class="nav-link"><i class="fas fa-robot"></i> Quản trị AI</a>
    </div>
</nav>
