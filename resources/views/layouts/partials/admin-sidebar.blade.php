     <nav class="sidebar">
         <div class="sidebar-header">
             <a href="#" class="sidebar-brand">
                 <i class="fas fa-wrench text-primary"></i>
                 <span>GARAGE PRO</span>
             </a>
         </div>

         <div class="sidebar-menu">
             <div class="menu-header">Tổng quan</div>
             <a href="dashboard.html" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                 <i class="fas fa-th-large"></i> Dashboard
             </a>

             <div class="menu-header">Vận hành</div>
             <a href="{{ route('reception.list') }}"
                 class="nav-link {{ request()->routeIs('reception.list') ? 'active' : '' }}">
                 <i class="fas fa-car"></i> Tiếp nhận xe
             </a>
             <a href="technician/jobs.html" class="nav-link">
                 <i class="fas fa-tools"></i> Kỹ thuật viên
             </a>
             <a href="warehouse/inventory.html" class="nav-link">
                 <i class="fas fa-boxes"></i> Kho vận
             </a>
             <a href="cashier/payments.html" class="nav-link">
                 <i class="fas fa-cash-register"></i> Thu ngân
             </a>

             <div class="menu-header">Hệ thống</div>
             <a href="{{ route('services.index') }}" class="nav-link">
                 <i class="fas fa-cogs"></i> Cấu hình
             </a>
             <a href="settings/ai-logs.html" class="nav-link">
                 <i class="fas fa-robot"></i> Quản trị AI
             </a>
         </div>

         <div class="sidebar-footer">
             <a href="#" class="nav-link text-danger p-0">
                 <i class="fas fa-sign-out-alt"></i> Đăng xuất
             </a>
         </div>
     </nav>
