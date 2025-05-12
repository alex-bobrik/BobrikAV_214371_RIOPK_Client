@extends('admin.layouts.app')

@section('title', 'Редактирование компании')
@section('page-title', 'Редактировать компанию')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.companies.update', $company) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label">Название</label>
                <input type="text" name="name" value="{{ old('name', $company->name) }}" class="form-control" required>
            </div>
        
            <div class="mb-3">
                <label class="form-label">Страна</label>
                <input type="text" name="country" value="{{ old('country', $company->country) }}" class="form-control" required>
            </div>
        
            <div class="mb-3">
                <label class="form-label">Тип</label>
                <select name="type" class="form-select" required>
                    <option value="insurer" {{ old('type', $company->type) === 'insurer' ? 'selected' : '' }}>Страховщик</option>
                    <option value="reinsurer" {{ old('type', $company->type) === 'reinsurer' ? 'selected' : '' }}>Перестраховщик</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Описание</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $company->description) }}</textarea>
            </div>
        
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
        
        
    </div>
</div>
@endsection
