<div class="sidebar">
    <div class="logo px-4 pb-4 border-bottom border-secondary">
        <h4 class="text-white mb-0">Reinsurance System</h4>
        <small class="text-muted">Client Panel</small>
    </div>
    
    <nav class="mt-3">
        <a href="{{ route('client.dashboard') }}" class="nav-item d-block px-4 py-2 text-decoration-none {{ request()->is('client/dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door me-2"></i> Главная
        </a>
        <a href="{{ route('client.companies.index') }}" class="nav-item d-block px-4 py-2 text-decoration-none {{ request()->is('client/companies*') ? 'active' : '' }}">
            <i class="bi bi-building me-2"></i> Компании
        </a>
        <a href="{{ route('client.contracts.index') }}" class="nav-item d-block px-4 py-2 text-decoration-none {{ request()->is('client/contracts*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text me-2"></i> Договоры
        </a>
        <a href="{{ route('client.claims.index') }}" class="nav-item d-block px-4 py-2 text-decoration-none {{ request()->is('client/claims*') ? 'active' : '' }}">
            <i class="bi bi-clock-history me-2"></i> Убытки
        </a>
        <a href="{{ route('client.reports') }}" class="nav-item d-block px-4 py-2 text-decoration-none {{ request()->is('client/reports') ? 'active' : '' }}">
            <i class="bi bi-graph-up me-2"></i> Отчеты
        </a>
        <a href="{{ route('client.settings') }}" class="nav-item d-block px-4 py-2 text-decoration-none {{ request()->is('client/settings') ? 'active' : '' }}">
            <i class="bi bi-gear me-2"></i> Настройки
        </a>
    </nav>
</div>