<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Панель перестраховщика</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 240px;
            background-color: #1e293b;
            color: #f8fafc;
            padding: 1.5rem 0;
            position: fixed;
            height: 100vh;
        }
        .main-content {
            margin-left: 240px;
            flex: 1;
            padding: 2rem;
        }
        .sidebar a {
            color: #f8fafc;
        }
        .sidebar a.active {
            background-color: #2563eb;
            color: #fff;
        }
    </style>
    @stack('styles')
</head>
<body>
    @include('underwriter.partials.sidebar')

    <main class="main-content">
        @include('underwriter.partials.header')
        <div class="container-fluid py-4">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
