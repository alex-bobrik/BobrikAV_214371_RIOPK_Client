@extends('client.layouts.app')

@section('title', 'Просмотр убытка')
@section('page-title', 'Информация об убытке')

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">Убыток №{{ $claim->id }}</h5>
    </div>

    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">Договор:</label>
                <div>№{{ $claim->contract->id }} — {{ $claim->contract->type }}</div>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Перестраховщик:</label>
                <div>{{ $claim->contract->reinsurer->name ?? '—' }}</div>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Сумма убытка:</label>
                <div>{{ number_format($claim->amount, 2, ',', ' ') }} ₽</div>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Дата подачи:</label>
                <div>{{ \Carbon\Carbon::parse($claim->filed_at)->format('d.m.Y H:i') }}</div>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Статус:</label>
                <div>
                    <span class="badge 
                        @if($claim->status === 'pending') bg-warning text-dark
                        @elseif($claim->status === 'approved') bg-success
                        @else bg-danger @endif">
                        {{ ucfirst($claim->status) }}
                    </span>
                </div>
            </div>

            <div class="col-12">
                <label class="form-label fw-bold">Описание:</label>
                <div class="bg-light p-3 rounded border">{{ $claim->description }}</div>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('client.claims.index') }}" class="btn btn-secondary">← К списку убытков</a>
        </div>
    </div>
</div>
@endsection
