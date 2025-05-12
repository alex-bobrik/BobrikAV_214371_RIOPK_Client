@extends('underwriter.layouts.app')

@section('title', 'Договор #' . $contract->id)
@section('page-title', 'Договор #' . $contract->id)

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="mb-3">Информация о договоре</h5>

        <dl class="row">
            <dt class="col-sm-3">Тип договора</dt>
            <dd class="col-sm-9">
                @switch($contract->type)
                    @case('quota') Квотный @break
                    @case('excess') Эксцедент @break
                    @case('facultative') Факультативный @break
                    @default {{ $contract->type }}
                @endswitch
            </dd>

            <dt class="col-sm-3">Страхователь</dt>
            <dd class="col-sm-9">{{ $contract->insurer->name ?? '—' }}</dd>

            <dt class="col-sm-3">Сумма покрытия</dt>
            <dd class="col-sm-9">₽{{ number_format($contract->coverage, 0, '', ' ') }}</dd>

            <dt class="col-sm-3">Дата начала</dt>
            <dd class="col-sm-9">{{ \Carbon\Carbon::parse($contract->start_date)->format('d.m.Y') }}</dd>

            <dt class="col-sm-3">Статус</dt>
            <dd class="col-sm-9">
                @switch($contract->status)
                    @case('active')<span class="badge bg-success">Активен</span>@break
                    @case('pending')<span class="badge bg-warning text-dark">На рассмотрении</span>@break
                    @case('canceled')<span class="badge bg-danger">Отменен</span>@break
                @endswitch
            </dd>
        </dl>

        @if($contract->status === 'pending')
            <div class="mt-4 d-flex gap-2">
                <form action="{{ route('underwriter.contracts.approve', $contract) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success"><i class="bi bi-check-circle"></i> Принять</button>
                </form>

                <form action="{{ route('underwriter.contracts.reject', $contract) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger"><i class="bi bi-x-circle"></i> Отклонить</button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
