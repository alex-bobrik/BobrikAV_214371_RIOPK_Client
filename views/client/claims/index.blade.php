@extends('client.layouts.app')

@section('title', 'Убытки')
@section('page-title', 'Зарегистрированные убытки')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Список убытков</h4>
    <a href="{{ route('client.claims.create') }}" class="btn btn-sm btn-primary">
        <i class="bi bi-plus-circle"></i> Новый убыток
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
                        <th>Договор</th>
                        <th>Перестраховщик</th>
                        <th>Сумма</th>
                        <th>Дата подачи</th>
                        <th>Статус</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($claims as $claim)
                        <tr>
                            <td>#{{ $claim->id }}</td>
                            <td>#{{ $claim->contract->id }}</td>
                            <td>{{ $claim->contract->reinsurer->name ?? '—' }}</td>
                            <td>₽{{ number_format($claim->amount, 0, '', ' ') }}</td>
                            <td>{{ \Carbon\Carbon::parse($claim->filed_at)->format('d.m.Y') }}</td>
                            <td>
                                @switch($claim->status)
                                    @case('pending')<span class="badge bg-warning text-dark">На рассмотрении</span>@break
                                    @case('approved')<span class="badge bg-success">Одобрен</span>@break
                                    @case('rejected')<span class="badge bg-danger">Отклонен</span>@break
                                @endswitch
                            </td>
                            <td class="text-end">
                                <a href="{{ route('client.claims.show', $claim) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($claim->status === 'pending')
                                    <a href="{{ route('client.claims.edit', $claim) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('client.claims.destroy', $claim) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Удалить этот убыток?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Убытки не найдены</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- <div class="mt-3">
            {{ $claims->links('vendor.pagination.bootstrap-5') }}
        </div> --}}
    </div>
</div>
@endsection
