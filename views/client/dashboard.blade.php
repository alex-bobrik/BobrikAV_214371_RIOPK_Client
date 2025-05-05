@extends('client.layouts.app')

@section('title', 'Главная панель')
@section('page-title', 'Обзор системы')

@section('content')
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-muted">Активные договоры</h5>
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">{{ $activeContractsCount }}</h2>
                    @if($contractsChangePercent > 0)
                        <span class="badge bg-success">+{{ $contractsChangePercent }}%</span>
                    @elseif($contractsChangePercent < 0)
                        <span class="badge bg-danger">{{ $contractsChangePercent }}%</span>
                    @else
                        <span class="badge bg-secondary">0%</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-muted">Цедированная сумма</h5>
                <h2 class="mb-0">₽{{ number_format($totalCoverage / 1000000, 2) }}M</h2>
                <small class="text-muted">за последние 3 месяца</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-muted">Ожидают решения</h5>
                <h2 class="mb-0">{{ $pendingClaimsCount }}</h2>
                <small class="text-muted">убытков</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Последние договоры</h5>
                <a href="{{ route('client.contracts.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus"></i> Новый договор
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Номер</th>
                                <th>Тип</th>
                                <th>Перестраховщик</th>
                                <th>Сумма</th>
                                <th>Статус</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentContracts as $contract)
                            <tr onclick="window.location='{{ route('client.contracts.show', $contract) }}'" style="cursor: pointer;">
                                <td>#{{ $contract->id }}</td>
                                <td>
                                    @switch($contract->type)
                                        @case('quota') Квотный @break
                                        @case('excess') Эксцедент @break
                                        @case('facultative') Факультативный @break
                                    @endswitch
                                </td>
                                <td>{{ $contract->reinsurer->name }}</td>
                                <td>₽{{ number_format($contract->coverage, 0, '', ' ') }}</td>
                                <td>
                                    @switch($contract->status)
                                        @case('active')<span class="status-badge status-active">Активен</span>@break
                                        @case('pending')<span class="status-badge status-pending">На рассмотрении</span>@break
                                        @case('canceled')<span class="status-badge status-canceled">Отменен</span>@break
                                    @endswitch
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Нет договоров</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Распределение договоров</h5>
            </div>
            <div class="card-body">
                <canvas id="contractsChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Pie chart
    const ctx = document.getElementById('contractsChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Квотные', 'Эксцедент', 'Факультативные'],
            datasets: [{
                data: [
                    {{ $contractsByType['quota'] ?? 0 }},
                    {{ $contractsByType['excess'] ?? 0 }},
                    {{ $contractsByType['facultative'] ?? 0 }}
                ],
                backgroundColor: [
                    '#3B82F6',
                    '#10B981',
                    '#F59E0B'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
</script>
@endpush
@endsection