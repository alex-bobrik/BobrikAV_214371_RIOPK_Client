<div class="header mb-4 d-flex justify-content-between align-items-center">
    <h1 class="h3">@yield('page-title', 'Панель перестраховщика')</h1>

    <div class="dropdown">
        <button class="btn btn-light dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown">
            {{ Auth::user()->name }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i> Выход</button>
                </form>
            </li>
        </ul>
    </div>
</div>
