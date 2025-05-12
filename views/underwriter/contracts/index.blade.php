@extends('underwriter.layouts.app')

@section('title', 'Договора')
@section('page-title', 'Договора вашей компании')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Список договоров</h4>
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
                        <th>Тип</th>
                        <th>Страхователь</th>
                        <th>Сумма покрытия</th>
                        <th>Дата начала</th>
                        <th>Статус</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contracts as $contract)
                        <tr>
                            <td>#{{ $contract->id }}</td>
                            <td>
                                @switch($contract->type)
                                    @case('quota') Квотный @break
                                    @case('excess') Эксцедент @break
                                    @case('facultative') Факультативный @break
                                    @default {{ $contract->type }}
                                @endswitch
                            </td>
                            <td>{{ $contract->insurer->name ?? '—' }}</td>
                            <td>₽{{ number_format($contract->coverage, 0, '', ' ') }}</td>
                            <td>{{ \Carbon\Carbon::parse($contract->start_date)->format('d.m.Y') }}</td>
                            <td>
                                @switch($contract->status)
                                    @case('active')<span class="badge bg-success">Активен</span>@break
                                    @case('pending')<span class="badge bg-warning text-dark">На рассмотрении</span>@break
                                    @case('canceled')<span class="badge bg-danger">Отменен</span>@break
                                @endswitch
                            </td>
                            <td class="text-end">
                                <a href="{{ route('underwriter.contracts.show', $contract) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Договора не найдены</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- <div class="mt-3">
            {{ $contracts->links('vendor.pagination.bootstrap-5') }}
        </div> --}}
    </div>
</div>
@endsection
