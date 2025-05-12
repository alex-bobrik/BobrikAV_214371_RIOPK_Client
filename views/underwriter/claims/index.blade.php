@extends('underwriter.layouts.app')

@section('title', 'Убытки')
@section('page-title', 'Зарегистрированные убытки')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Список убытков</h4>
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
                        <th>Договор</th>
                        <th>Сумма</th>
                        <th>Описание</th>
                        <th>Статус</th>
                        <th>Дата</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($claims as $claim)
                        <tr>
                            <td>#{{ $claim->id }}</td>
                            <td>Договор #{{ $claim->contract->id }}</td>
                            <td>₽{{ number_format($claim->amount, 0, '', ' ') }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($claim->description, 40) }}</td>
                            <td>
                                @switch($claim->status)
                                    @case('pending')<span class="badge bg-warning text-dark">На рассмотрении</span>@break
                                    @case('approved')<span class="badge bg-success">Одобрен</span>@break
                                    @case('rejected')<span class="badge bg-danger">Отклонен</span>@break
                                @endswitch
                            </td>
                            <td>{{ $claim->created_at->format('d.m.Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('underwriter.claims.show', $claim) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">Убытки не найдены</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
