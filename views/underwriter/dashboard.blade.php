@extends('underwriter.layouts.app')

@section('title', 'Панель перестраховщика')
@section('page-title', 'Добро пожаловать, ' . auth()->user()->name)

@section('content')
<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 p-4">
            <h6 class="text-muted">Статус договоров</h6>
            <canvas id="contractChart" style="height: 300px;"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0 p-4">
            <h6 class="text-muted">Статус платежей</h6>
            <canvas id="paymentChart" style="height: 300px;"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const contractCtx = document.getElementById('contractChart').getContext('2d');
    const contractChart = new Chart(contractCtx, {
        type: 'pie',
        data: {
            labels: @json($contractStatuses['labels']),
            datasets: [{
                data: @json($contractStatuses['data']),
                backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw + ' договоров';
                        }
                    }
                }
            }
        },
    });

    const paymentCtx = document.getElementById('paymentChart').getContext('2d');
    const paymentChart = new Chart(paymentCtx, {
        type: 'bar',
        data: {
            labels: @json($paymentStatuses['labels']),
            datasets: [{
                label: 'Количество платежей',
                data: @json($paymentStatuses['data']),
                backgroundColor: ['#007bff', '#28a745', '#dc3545'],
                borderColor: ['#007bff', '#28a745', '#dc3545'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw + ' платежей';
                        }
                    }
                }
            }
        },
    });
</script>
@endpush
@endsection
