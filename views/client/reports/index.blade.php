@extends('client.layouts.app')

@section('title', 'Отчеты')
@section('page-title', 'Аналитика и отчеты')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<style>
    .chart-container {
        height: 300px;
        margin-bottom: 2rem;
    }
</style>
@endpush

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">Отчеты</h5>
    </div>
    
    <div class="card-body">
        <ul class="nav nav-tabs mb-4" id="reportsTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="contracts-tab" data-bs-toggle="tab" data-bs-target="#contracts" type="button">
                    Договоры
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="claims-tab" data-bs-toggle="tab" data-bs-target="#claims" type="button">
                    Убытки
                </button>
            </li>
        </ul>
        
        <div class="tab-content" id="reportsTabContent">
            <div class="tab-pane fade show active" id="contracts" role="tabpanel">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <select class="form-select" id="contractsPeriod">
                            <option value="month">За последний месяц</option>
                            <option value="quarter" selected>За последний квартал</option>
                            <option value="year">За последний год</option>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">Распределение по типам</div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="contractsByTypeChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">Распределение по статусам</div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="contractsByStatusChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">Динамика по месяцам</div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="contractsByMonthChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="tab-pane fade" id="claims" role="tabpanel">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <select class="form-select" id="claimsPeriod">
                            <option value="month">За последний месяц</option>
                            <option value="quarter" selected>За последний квартал</option>
                            <option value="year">За последний год</option>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">Распределение по статусам</div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="claimsByStatusChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">Общая сумма убытков</div>
                            <div class="card-body">
                                <h2 id="totalClaimsAmount" class="text-center">₽0</h2>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">Динамика по месяцам</div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="claimsByMonthChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Инициализация графиков
    const contractsByTypeChart = initChart('contractsByTypeChart', 'doughnut');
    const contractsByStatusChart = initChart('contractsByStatusChart', 'doughnut');
    const contractsByMonthChart = initChart('contractsByMonthChart', 'line');
    const claimsByStatusChart = initChart('claimsByStatusChart', 'doughnut');
    const claimsByMonthChart = initChart('claimsByMonthChart', 'line');
    
    function initChart(id, type) {
        const ctx = document.getElementById(id).getContext('2d');
        return new Chart(ctx, {
            type: type,
            data: { labels: [], datasets: [{ data: [], backgroundColor: [] }] },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        });
    }
    
    // Загрузка данных по договорам
    function loadContractsReport(period) {
        $.ajax({
            url: '/api/v1/reports/contracts',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('access_token')
            },
            data: { period },
            success: function(response) {
                // Типы договоров
                updateChart(contractsByTypeChart, {
                    'Квотные': response.by_type.quota || 0,
                    'Эксцедент': response.by_type.excess || 0,
                    'Факультативные': response.by_type.facultative || 0
                });
                
                // Статусы договоров
                updateChart(contractsByStatusChart, {
                    'Активные': response.by_status.active || 0,
                    'На рассмотрении': response.by_status.pending || 0,
                    'Отмененные': response.by_status.canceled || 0
                });
                
                // По месяцам
                updateLineChart(contractsByMonthChart, response.by_month);
            }
        });
    }
    
    // Загрузка данных по убыткам
    function loadClaimsReport(period) {
        $.ajax({
            url: '/api/v1/reports/claims',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('access_token')
            },
            data: { period },
            success: function(response) {
                // Статусы убытков
                updateChart(claimsByStatusChart, {
                    'На рассмотрении': response.by_status.pending || 0,
                    'Одобренные': response.by_status.approved || 0,
                    'Отклоненные': response.by_status.rejected || 0,
                    'Оплаченные': response.by_status.paid || 0
                });
                
                // Общая сумма
                $('#totalClaimsAmount').text('₽' + new Intl.NumberFormat('ru-RU').format(response.total_amount || 0));
                
                // По месяцам
                updateLineChart(claimsByMonthChart, response.by_month);
            }
        });
    }
    
    // Обновление круговой диаграммы
    function updateChart(chart, data) {
        chart.data.labels = Object.keys(data);
        chart.data.datasets[0].data = Object.values(data);
        chart.data.datasets[0].backgroundColor = [
            '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'
        ].slice(0, Object.keys(data).length);
        chart.update();
    }
    
    // Обновление линейного графика
    function updateLineChart(chart, data) {
        chart.data.labels = data.map(item => item.month);
        chart.data.datasets[0].data = data.map(item => item.count);
        chart.data.datasets[0].backgroundColor = '#3B82F6';
        chart.data.datasets[0].borderColor = '#3B82F6';
        chart.data.datasets[0].fill = false;
        chart.update();
    }
    
    // Обработчики изменения периода
    $('#contractsPeriod').change(function() {
        loadContractsReport($(this).val());
    });
    
    $('#claimsPeriod').change(function() {
        loadClaimsReport($(this).val());
    });
    
    // Первоначальная загрузка
    loadContractsReport('quarter');
    loadClaimsReport('quarter');
    
    // Переключение вкладок
    $('#reportsTab button').on('shown.bs.tab', function(event) {
        const target = $(event.target).attr('data-bs-target');
        if (target === '#claims' && $('#claimsPeriod').val()) {
            loadClaimsReport($('#claimsPeriod').val());
        } else if (target === '#contracts' && $('#contractsPeriod').val()) {
            loadContractsReport($('#contractsPeriod').val());
        }
    });
});
</script>
@endpush