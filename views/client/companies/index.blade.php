@extends('client.layouts.app')

@section('title', 'Компании')
@section('page-title', 'Список компаний')

@section('content')
<div class="card shadow-sm">
    <div class="card-body table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Страна</th>
                    <th>Тип</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($companies as $company)
                    <tr>
                        <td>{{ $company->name }}</td>
                        <td>{{ $company->country }}</td>
                        <td>
                            @switch($company->type)
                                @case('insurer') Страховщик @break
                                @case('reinsurer') Перестраховщик @break
                                @default {{ $company->type }}
                            @endswitch
                        </td>
                        <td class="text-end">
                            <a href="{{ route('client.companies.show', $company) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Просмотр
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Нет доступных компаний</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
