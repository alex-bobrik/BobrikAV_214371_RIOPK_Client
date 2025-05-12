@extends('underwriter.layouts.app')

@section('title', 'Убыток #' . $claim->id)
@section('page-title', 'Убыток #' . $claim->id)

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="mb-3">Информация об убытке</h5>

        <dl class="row">
            <dt class="col-sm-3">Договор</dt>
            <dd class="col-sm-9">#{{ $claim->contract->id }}</dd>

            <dt class="col-sm-3">Сумма</dt>
            <dd class="col-sm-9">₽{{ number_format($claim->amount, 0, '', ' ') }}</dd>

            <dt class="col-sm-3">Описание</dt>
            <dd class="col-sm-9">{{ $claim->description }}</dd>

            <dt class="col-sm-3">Статус</dt>
            <dd class="col-sm-9">
                @switch($claim->status)
                    @case('pending')<span class="badge bg-warning text-dark">На рассмотрении</span>@break
                    @case('approved')<span class="badge bg-success">Одобрен</span>@break
                    @case('rejected')<span class="badge bg-danger">Отклонен</span>@break
                @endswitch
            </dd>

            <dt class="col-sm-3">Создан</dt>
            <dd class="col-sm-9">{{ $claim->created_at->format('d.m.Y H:i') }}</dd>
        </dl>

        @if($claim->status === 'pending')
            <div class="mt-4 d-flex gap-2">
                <form action="{{ route('underwriter.claims.approve', $claim) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success"><i class="bi bi-check-circle"></i> Принять</button>
                </form>

                <form action="{{ route('underwriter.claims.reject', $claim) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger"><i class="bi bi-x-circle"></i> Отклонить</button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
