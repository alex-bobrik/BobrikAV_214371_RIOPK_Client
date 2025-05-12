@extends('client.layouts.app')

@section('title', 'Просмотр договора')
@section('page-title', 'Информация о договоре')

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">Договор №{{ $contract->id }}</h5>
    </div>

    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">Тип договора:</label>
                <div>
                    @switch($contract->type)
                        @case('quota') Квотный @break
                        @case('excess') Эксцедент @break
                        @case('facultative') Факультативный @break
                        @default {{ $contract->type }}
                    @endswitch
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Перестраховщик:</label>
                <div>{{ $contract->reinsurer->name ?? '—' }}</div>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Сумма покрытия:</label>
                <div>{{ number_format($contract->coverage, 2, ',', ' ') }} ₽</div>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Дата начала:</label>
                <div>{{ \Carbon\Carbon::parse($contract->start_date)->format('d.m.Y') }}</div>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Дата окончания:</label>
                <div>{{ \Carbon\Carbon::parse($contract->end_date)->format('d.m.Y') }}</div>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Статус:</label>
                <div>
                    <span class="badge 
                        @if($contract->status === 'active') bg-success
                        @elseif($contract->status === 'pending') bg-warning text-dark
                        @else bg-danger @endif">
                        {{ ucfirst($contract->status) }}
                    </span>
                </div>
            </div>

        </div>

        <div class="mt-4">
            <a href="{{ route('client.contracts.index') }}" class="btn btn-secondary">← К списку договоров</a>
        </div>
    </div>
</div>
@endsection
