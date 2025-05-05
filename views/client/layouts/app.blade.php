<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Система перестрахования</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    @stack('styles')
    <style>
        :root {
            --primary: #2563eb;
            --dark: #1e293b;
            --light: #f8fafc;
            --sidebar: #1e293b;
            --sidebar-text: #f8fafc;
        }
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background-color: #f1f5f9;
            color: var(--dark);
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 240px;
            background-color: var(--sidebar);
            color: var(--sidebar-text);
            padding: 1.5rem 0;
            position: fixed;
            height: 100vh;
        }
        .main-content {
            margin-left: 240px;
            flex: 1;
            padding: 2rem;
        }
        .stat-card .value {
            font-size: 1.75rem;
            font-weight: 600;
        }
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .status-active {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-pending {
            background-color: #fef9c3;
            color: #854d0e;
        }
        .status-canceled {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
    @include('client.partials.sidebar')
    
    <main class="main-content">
        @include('client.partials.header')
        
        <div class="container-fluid py-4">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>