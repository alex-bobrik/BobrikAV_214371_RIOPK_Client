@extends('admin.layouts.app')

@section('title', 'Панель администратора')
@section('page-title', 'Добро пожаловать, Администратор')

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted">Пользователи</h6>
                    <div class="value">{{ $userCount }}</div>
                </div>
                <i class="bi bi-people fs-2 text-primary"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted">Компании</h6>
                    <div class="value">{{ $companyCount }}</div>
                </div>
                <i class="bi bi-building fs-2 text-primary"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted">Договоры</h6>
                    <div class="value">{{ $contractCount }}</div>
                </div>
                <i class="bi bi-file-earmark-text fs-2 text-primary"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Договора по статусам</h5>
            </div>
            <div class="card-body">
                <canvas id="contractStatusChart" height="250"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Убытки по договорам</h5>
            </div>
            <div class="card-body">
                <canvas id="claimsChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Платежи по договорам</h5>
            </div>
            <div class="card-body">
                <canvas id="paymentsChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- @section('scripts') --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log('Script loaded');

    const contractStatusData = {!! json_encode($contractStatusData) !!};
    const claimsData = {!! json_encode($claimsData) !!};
    const paymentsData = {!! json_encode($paymentsData) !!};

    console.log('contractStatusData', contractStatusData);
    console.log('claimsData', claimsData);
    console.log('paymentsData', paymentsData);

    const ctx1 = document.getElementById('contractStatusChart');
    if (ctx1) {
        new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: contractStatusData.labels,
                datasets: [{
                    data: contractStatusData.data,
                    backgroundColor: ['#4caf50', '#ff9800', '#f44336'],
                }]
            }
        });
    }

    const ctx2 = document.getElementById('claimsChart');
    if (ctx2) {
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: claimsData.labels,
                datasets: [{
                    label: 'Убытки',
                    data: claimsData.data,
                    backgroundColor: '#ff9800',
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    const ctx3 = document.getElementById('paymentsChart');
    if (ctx3) {
        new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: paymentsData.labels,
                datasets: [{
                    label: 'Платежи',
                    data: paymentsData.data,
                    backgroundColor: '#4caf50',
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
});
</script>
{{-- @endsection --}}
