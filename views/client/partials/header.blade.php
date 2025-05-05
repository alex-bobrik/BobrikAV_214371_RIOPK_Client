<div class="header mb-4">
    <h1 class="h3">@yield('page-title', 'Главная панель')</h1>
    
    <div class="dropdown">
        <button class="btn btn-light dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown">
            <span class="me-2">{{ Auth::user()->name }}</span>
            <span class="badge bg-primary">{{ Auth::user()->company->name }}</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="{{ route('client.settings') }}"><i class="bi bi-person me-2"></i> Профиль</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i> Выход</button>
                </form>
            </li>
        </ul>
    </div>
</div>