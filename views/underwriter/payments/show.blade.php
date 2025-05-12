@extends('underwriter.layouts.app')

@section('title', 'Просмотр платежа')
@section('page-title', 'Детали платежа')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Платёж #{{ $payment->id }}</h4>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <p><strong>Сумма:</strong> ₽{{ number_format($payment->amount, 2, ',', ' ') }}</p>
        <p><strong>Тип:</strong> Убыток</p>
        <p><strong>Статус:</strong>
            @switch($payment->status)
                @case('pending') <span class="badge bg-warning text-dark">В ожидании</span> @break
                @case('paid') <span class="badge bg-success">Одобрен</span> @break
                @case('failed') <span class="badge bg-danger">Отклонён</span> @break
            @endswitch
        </p>
        <p><strong>Дата платежа:</strong> {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d.m.Y') : '—' }}</p>

        @if($payment->status === 'pending')
            <div class="mt-4 d-flex gap-2">
                <form action="{{ route('underwriter.payments.approve', $payment) }}" method="POST">
                    @csrf
                    <button class="btn btn-success" onclick="return confirm('Подтвердить платёж?')">Принять</button>
                </form>
                <form action="{{ route('underwriter.payments.reject', $payment) }}" method="POST">
                    @csrf
                    <button class="btn btn-danger" onclick="return confirm('Отклонить платёж?')">Отклонить</button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
