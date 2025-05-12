<div class="sidebar">
    <div class="logo px-4 pb-4 border-bottom border-secondary">
        <h4 class="text-white mb-0">Reinsurance Admin</h4>
        <h5 class="text-white mb-0">Admin Panel</small>
        </div>

    <nav class="mt-3">
        <a href="{{ route('admin.dashboard') }}" class="nav-item d-block px-4 py-2 text-decoration-none {{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i> Панель
        </a>
        <a href="{{ route('admin.users.index') }}" class="nav-item d-block px-4 py-2 text-decoration-none {{ request()->is('admin/users*') ? 'active' : '' }}">
            <i class="bi bi-people me-2"></i> Пользователи
        </a>
        <a href="{{ route('admin.companies.index') }}" class="nav-item d-block px-4 py-2 text-decoration-none {{ request()->is('admin/companies*') ? 'active' : '' }}">
            <i class="bi bi-building me-2"></i> Компании
        </a>
        <a href="{{ route('admin.contracts.index') }}" class="nav-item d-block px-4 py-2 text-decoration-none {{ request()->is('admin/contracts*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text me-2"></i> Договоры
        </a>
    </nav>
</div>
