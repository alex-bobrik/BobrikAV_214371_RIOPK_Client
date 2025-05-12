@extends('admin.layouts.app')

@section('title', 'Новая компания')
@section('page-title', 'Добавить компанию')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.companies.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Название</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Страна</label>
                <input type="text" name="country" value="{{ old('country') }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Тип</label>
                <select name="type" class="form-select" required>
                    <option value="insurer" {{ old('type') === 'insurer' ? 'selected' : '' }}>Страховщик</option>
                    <option value="reinsurer" {{ old('type') === 'reinsurer' ? 'selected' : '' }}>Перестраховщик</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Описание</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
</div>
@endsection
