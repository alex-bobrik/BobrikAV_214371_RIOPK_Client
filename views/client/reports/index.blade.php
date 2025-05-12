@extends('client.layouts.app')

@section('title', 'Отчеты')
@section('page-title', 'Отчеты по убыткам и договорам')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    flatpickr('#date_from', { dateFormat: 'Y-m-d' });
    flatpickr('#date_to', { dateFormat: 'Y-m-d' });

    $('#loadReport').click(function () {
        const from = $('#date_from').val();
        const to = $('#date_to').val();
        const currentUser = @json(Auth::user());

        if (!from || !to) {
            alert('Укажите обе даты');
            return;
        }

        $('#reportContent').html('<div class="text-center my-4"><div class="spinner-border text-primary" role="status"></div></div>');

        fetch(`/api/v1/reports`, {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + (localStorage.getItem('access_token') || ''),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                date_from: from,
                date_to: to,
                user: currentUser
            })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) throw new Error(data.message || 'Ошибка загрузки');

            $('#reportContent').html(`
                <div id="summary"></div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <canvas id="coverageChart"></canvas>
                    </div>
                    <div class="col-md-6">
                        <canvas id="claimsChart"></canvas>
                    </div>
                </div>
            `);

            try {
                renderReport(data.report, data.charts);
            } catch (error) {
                console.error('Ошибка рендеринга:', error);
            }
        })
        .catch(error => {
            $('#reportContent').html(`<div class="alert alert-danger">${error.message || 'Ошибка запроса'}</div>`);
        });
    });

    $('#exportReport').click(function () {
        const from = $('#date_from').val();
        const to = $('#date_to').val();

        if (!from || !to) {
            alert('Укажите обе даты');
            return;
        }

        window.open(`/api/v1/reports/export?from=${from}&to=${to}&token=${localStorage.getItem('access_token') || ''}`, '_blank');
    });

    function renderReport(report, charts) {
        let summary = `
            <h5>Сводка</h5>
            <ul>
                <li>Количество договоров: ${report.contracts_count}</li>
                <li>Количество убытков: ${report.claims_count}</li>
                <li>Общая сумма покрытий: ₽${report.total_coverage.toLocaleString()}</li>
                <li>Общая сумма убытков: ₽${report.total_claims.toLocaleString()}</li>
            </ul>
        `;
        $('#summary').html(summary);

        const coverageChartElement = document.getElementById('coverageChart');
        if (coverageChartElement) {
            try {
                const reinsurerLabels = Object.keys(charts.coverage_by_reinsurer);
                const reinsurerData = Object.values(charts.coverage_by_reinsurer);

                new Chart(coverageChartElement, {
                    type: 'pie',
                    data: {
                        labels: reinsurerLabels,
                        datasets: [{
                            label: 'Сумма покрытия',
                            data: reinsurerData,
                            backgroundColor: reinsurerLabels.map(() => randomColor())
                        }]
                    },
                    options: {
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    boxWidth: 20,
                                    padding: 15,
                                    usePointStyle: true,
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        let value = context.raw || 0;
                                        return `${label}: ₽${value.toLocaleString()}`;
                                    }
                                }
                            }
                        },
                        layout: {
                            padding: 20
                        }
                    }
                });
            } catch (error) {
                console.error('Ошибка при создании диаграммы покрытия:', error);
            }
        }

        const claimsChartElement = document.getElementById('claimsChart');
        if (claimsChartElement) {
            try {
                const monthLabels = Object.keys(charts.claims_by_month);
                const monthData = Object.values(charts.claims_by_month);

                new Chart(claimsChartElement, {
                    type: 'bar',
                    data: {
                        labels: monthLabels,
                        datasets: [{
                            label: 'Сумма убытков',
                            data: monthData,
                            backgroundColor: '#0d6efd'
                        }]
                    },
                    options: {
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            } catch (error) {
                console.error('Ошибка при создании диаграммы убытков:', error);
            }
        }
    }

    function randomColor() {
        return `hsl(${Math.floor(Math.random() * 360)}, 70%, 60%)`;
    }
});
</script>
@endpush

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Отчетный период</h5>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label for="date_from" class="form-label">Дата начала</label>
                <input type="text" class="form-control" id="date_from" placeholder="YYYY-MM-DD">
            </div>
            <div class="col-md-4">
                <label for="date_to" class="form-label">Дата окончания</label>
                <input type="text" class="form-control" id="date_to" placeholder="YYYY-MM-DD">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <div class="btn-group w-100">
                    <button class="btn btn-primary" id="loadReport">
                        <i class="bi bi-search"></i> Показать отчет
                    </button>
                </div>
            </div>
        </div>
        <div id="reportContent" class="border rounded p-3 bg-light">
            <p class="text-muted">Выберите период и нажмите «Показать отчет»</p>
            <div id="summary"></div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <canvas id="coverageChart"></canvas>
                </div>
                <div class="col-md-6">
                    <canvas id="claimsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
