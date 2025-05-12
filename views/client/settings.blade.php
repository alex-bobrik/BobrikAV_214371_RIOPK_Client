@extends('client.layouts.app')

@section('title', 'Настройки профиля')
@section('page-title', 'Настройки клиента')

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">Профиль</h5>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('client.settings.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Страховая компания</label>
                <input type="text" class="form-control" value="{{ Auth::user()->company->name }}" disabled>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Имя пользователя</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>

                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
        </form>

        <div class="card shadow-sm mt-4">
            <div class="card-header">
                <h5 class="mb-0">Смена пароля</h5>
            </div>
            <div class="card-body">
                @if(session('password_success'))
                    <div class="alert alert-success">{{ session('password_success') }}</div>
                @endif
        
                @if(session('password_error'))
                    <div class="alert alert-danger">{{ session('password_error') }}</div>
                @endif
        
                <form method="POST" action="{{ route('client.settings.password') }}">
                    @csrf
                    @method('PUT')
        
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Текущий пароль</label>
                        <input type="password" name="current_password" id="current_password"
                               class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Новый пароль</label>
                        <input type="password" name="new_password" id="new_password"
                               class="form-control @error('new_password') is-invalid @enderror" required>
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Подтверждение нового пароля</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                               class="form-control" required>
                    </div>
        
                    <button type="submit" class="btn btn-warning">Обновить пароль</button>
                </form>
            </div>
        </div>
        
    </div>
</div>
@endsection
