@extends('underwriter.layouts.app')

@section('title', 'Платежи')
@section('page-title', 'История платежей')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Список платежей</h4>
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
                        <th>Сумма</th>
                        <th>Тип</th>
                        <th>Статус</th>
                        <th>Дата платежа</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>#{{ $payment->id }}</td>
                            <td>₽{{ number_format($payment->amount, 2, ',', ' ') }}</td>
                            <td>Убыток</td>
                            <td>
                                @switch($payment->status)
                                    @case('pending') <span class="badge bg-warning text-dark">В ожидании</span> @break
                                    @case('paid') <span class="badge bg-success">Одобрен</span> @break
                                    @case('failed') <span class="badge bg-danger">Отклонён</span> @break
                                @endswitch
                            </td>
                            <td>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d.m.Y') : '—' }}</td>
                            <td class="text-end">
                                <a href="{{ route('underwriter.payments.show', $payment) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Платежи не найдены</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
