<nav class="sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="adminSidebar">
    <div class="sidebar-header d-flex align-items-center justify-content-between">
        <a href="{{ route('adminDashboard') }}" class="sidebar-brand fw-bold text-decoration-none">
            <img src="{{ asset('storage/logo/logo-icon.png') }}" alt="CBAUTO" class="logo-icon-img">
            CBAUTO
        </a>
        <button type="button" class="btn-close btn-close-white d-lg-none" data-bs-dismiss="offcanvas"
            data-bs-target="#adminSidebar"></button>
    </div>

    <div class="sidebar-menu">
        <div class="menu-header">Tổng quan</div>
        <a href="{{ route('adminDashboard') }}"
            class="nav-link {{ request()->routeIs('adminDashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i> <span>Dashboard</span>
        </a>

        <div class="menu-header">Vận hành</div>

        <a href="{{ route('admin.booking') }}"
            class="nav-link {{ request()->routeIs('admin.booking') ? 'active' : '' }}">
            <i class="fa-solid fa-calendar-days"></i> <span>Quản lý đặt lịch</span>
        </a>
        @can('is-super-admin')
            <a href="{{ route('admin.customers.index') }}"
                class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> <span>Quản lý khách hàng</span>
            </a>
        @endcan

        @php $isReceptionActive = request()->routeIs('reception.*'); @endphp
        <div class="dropdown w-100">
            <button class="dropdown-toggle {{ $isReceptionActive ? 'active' : '' }}" type="button"
                data-bs-toggle="dropdown" data-bs-display="static" data-bs-auto-close="outside"
                aria-expanded="{{ $isReceptionActive ? 'true' : 'false' }}">
                <i class="fas fa-car"></i> <span>Tiếp nhận xe</span>
            </button>
            <ul class="dropdown-menu {{ $isReceptionActive ? 'show' : '' }}">
                <li>
                    <a class="dropdown-item {{ request()->routeIs('reception.create') ? 'active' : '' }}"
                        href="{{ route('reception.create') }}">
                        <i class="fas fa-circle"></i> Tạo đơn tiếp nhận mới
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ request()->routeIs('reception.list') ? 'active' : '' }}"
                        href="{{ route('reception.list') }}">
                        <i class="fas fa-circle"></i> Danh sách tiếp nhận
                    </a>
                </li>
            </ul>
        </div>

        @php $isWarehouseActive = request()->routeIs('warehouse.*'); @endphp
        <div class="dropdown w-100">
            <button class="dropdown-toggle {{ $isWarehouseActive ? 'active' : '' }}" type="button"
                data-bs-toggle="dropdown" data-bs-display="static" data-bs-auto-close="outside"
                aria-expanded="{{ $isWarehouseActive ? 'true' : 'false' }}">
                <i class="fas fa-boxes"></i> <span>Kho vận</span>
            </button>
            <ul class="dropdown-menu {{ $isWarehouseActive ? 'show' : '' }}">
                <li>
                    <a class="dropdown-item {{ request()->routeIs('warehouse.inventory') ? 'active' : '' }}"
                        href="{{ route('warehouse.inventory') }}">
                        <i class="fas fa-circle"></i> Tồn kho
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ request()->routeIs('warehouse.part-requests') ? 'active' : '' }}"
                        href="{{ route('warehouse.part-requests') }}">
                        <i class="fas fa-circle"></i> Yêu cầu cấp vật tư
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ request()->routeIs('warehouse.stock-transfer') ? 'active' : '' }}"
                        href="{{ route('warehouse.stock-transfer') }}">
                        <i class="fas fa-circle"></i> Luân chuyển kho
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ request()->routeIs('warehouse.transfer.history') ? 'active' : '' }}"
                        href="{{ route('warehouse.transfer.history') }}">
                        <i class="fas fa-circle"></i> Lịch sử luân chuyển
                    </a>
                </li>
            </ul>
        </div>

        <a href="{{ route('payment.index') }}" class="nav-link {{ request()->routeIs('payment.*') ? 'active' : '' }}">
            <i class="fas fa-credit-card"></i> <span>Quản lý thanh toán</span>
        </a>
        @can('is-super-admin')
            <div class="menu-header">Hệ thống</div>
            <a href="{{ route('services.index') }}" class="nav-link">
                <i class="fas fa-cogs"></i> <span>Cấu hình (Thiết lập)</span>
            </a>
            <a href="{{ route('admin.ai-logs.index') }}" class="nav-link">
                <i class="fas fa-robot"></i> <span>Quản trị AI</span>
            </a>
        @endcan
    </div>

    <div class="sidebar-footer">
        <a href="#" class="nav-link text-danger p-0"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> <span>Đăng xuất</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</nav>
