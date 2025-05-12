<div class="sidebar">
    <div class="logo px-4 pb-4 border-bottom border-secondary">
        <h4 class="text-white mb-0">Reinsurance</h4>
        <h5 class="text-white mb-0">Underwriter Panel</small>
    </div>

    <nav class="mt-3">
        <a href="{{ route('underwriter.dashboard') }}" class="nav-item d-block px-4 py-2 text-decoration-none {{ request()->routeIs('underwriter.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i> Панель
        </a>
        <a href="{{ route('underwriter.contracts.index') }}" class="nav-item d-block px-4 py-2 text-decoration-none {{ request()->routeIs('underwriter.contracts.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text me-2"></i> Договоры
        </a>
        <a href="{{ route('underwriter.claims.index') }}" class="nav-item d-block px-4 py-2 text-decoration-none {{ request()->is('underwriter/claims*') ? 'active' : '' }}">
            <i class="bi bi-exclamation-octagon me-2"></i> Убытки
        </a>        
        <a href="{{ route('underwriter.payments.index') }}" class="nav-item d-block px-4 py-2 text-decoration-none {{ request()->routeIs('underwriter.payments.*') ? 'active' : '' }}">
            <i class="bi bi-cash-stack me-2"></i> История платежей
        </a>
    </nav>
</div>
