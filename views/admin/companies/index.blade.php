@extends('admin.layouts.app')

@section('title', 'Компании')
@section('page-title', 'Зарегистрированные компании')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Список компаний</h4>
    <a href="{{ route('admin.companies.create') }}" class="btn btn-sm btn-primary">
        <i class="bi bi-plus-circle"></i> Новая компания
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Страна</th>
                        <th>Тип</th>
                        <th>Создана</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($companies as $company)
                        <tr>
                            <td>#{{ $company->id }}</td>
                            <td>{{ $company->name }}</td>
                            <td>{{ $company->country }}</td>
                            <td>
                                @if($company->type === 'insurer')
                                    <span class="badge bg-primary">Страховщик</span>
                                @elseif($company->type === 'reinsurer')
                                    <span class="badge bg-info text-dark">Перестраховщик</span>
                                @else
                                    <span class="badge bg-secondary">—</span>
                                @endif
                            </td>
                            <td>{{ $company->created_at->format('d.m.Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.companies.destroy', $company) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Удалить эту компанию?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Компании не найдены</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
