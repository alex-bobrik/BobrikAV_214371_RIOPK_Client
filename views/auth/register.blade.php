<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f8f9fa;
        }
        .register-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-control:focus, .select2-selection--single:focus {
            border-color: #0d6efd !important;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
        }
        .badge-type {
            font-size: 0.75rem;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-container">
            <h2 class="text-center mb-4">Регистрация клиента</h2>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">ФИО</label>
                    <input type="text" class="form-control" id="name" name="name" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Пароль</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Подтвердите пароль</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>
                <div class="mb-3">
                    <label for="company_id" class="form-label">Страховая компания</label>
                    <select class="form-select" id="company_id" name="company_id" required>
                        <option value="" disabled selected>Выберите страховую компанию</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" data-type="{{ $company->type }}">
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('company_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
                </div>
            </form>

            <div class="mt-3 text-center">
                <p>Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a></p>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#company_id').select2({
                width: '100%',
                templateResult: formatCompany,
                templateSelection: formatCompanySelection,
                placeholder: 'Выберите страховую компанию',
                language: 'ru'
            });

            function formatCompany (state) {
                if (!state.id) return state.text;

                const type = $(state.element).data('type');
                const badge = type === 'insurer' 
                    ? '<span class="badge bg-primary badge-type">Страховщик</span>'
                    : '<span class="badge bg-danger badge-type">Перестраховщик</span>';

                return $(`<span>${state.text} ${badge}</span>`);
            }

            function formatCompanySelection (state) {
                if (!state.id) return state.text;

                const type = $(state.element).data('type');
                const badge = type === 'insurer' 
                    ? ' <span class="badge bg-primary badge-type">Страховщик</span>'
                    : ' <span class="badge bg-danger badge-type">Перестраховщик</span>';

                return $(`<span>${state.text}${badge}</span>`);
            }
        });
    </script>
</body>
</html>
