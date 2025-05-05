@extends('client.layouts.app')

@section('title', 'Договоры перестрахования')
@section('page-title', 'Управление договорами')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<style>
    .contract-status {
        font-size: 0.75rem;
    }
    #contractsTable tr {
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Список договоров</h5>
        <button id="newContractBtn" class="btn btn-primary btn-sm">
            <i class="bi bi-plus me-1"></i> Новый договор
        </button>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table id="contractsTable" class="table table-hover w-100">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Тип</th>
                        <th>Перестраховщик</th>
                        <th>Покрытие</th>
                        <th>Статус</th>
                        <th>Дата</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create/Edit Modal -->
<div class="modal fade" id="contractModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Новый договор</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="contractForm">
                <div class="modal-body">
                    <input type="hidden" id="contractId">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="type" class="form-label">Тип договора</label>
                            <select class="form-select" id="type" required>
                                <option value="">Выберите тип</option>
                                <option value="quota">Квотный</option>
                                <option value="excess">Эксцедент</option>
                                <option value="facultative">Факультативный</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="reinsurer_id" class="form-label">Перестраховщик</label>
                            <select class="form-select" id="reinsurer_id" required>
                                <option value="">Выберите компанию</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="premium" class="form-label">Премия (₽)</label>
                            <input type="number" class="form-control" id="premium" required>
                        </div>
                        <div class="col-md-6">
                            <label for="coverage" class="form-label">Сумма покрытия (₽)</label>
                            <input type="number" class="form-control" id="coverage" required>
                        </div>
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Дата начала</label>
                            <input type="date" class="form-control" id="start_date" required>
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">Дата окончания</label>
                            <input type="date" class="form-control" id="end_date" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewContractModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Договор #<span id="viewContractId"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Основная информация</h6>
                        <table class="table table-sm">
                            <tr>
                                <th>Тип:</th>
                                <td id="viewType"></td>
                            </tr>
                            <tr>
                                <th>Перестраховщик:</th>
                                <td id="viewReinsurer"></td>
                            </tr>
                            <tr>
                                <th>Статус:</th>
                                <td><span id="viewStatus" class="badge"></span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Финансовые условия</h6>
                        <table class="table table-sm">
                            <tr>
                                <th>Премия:</th>
                                <td id="viewPremium"></td>
                            </tr>
                            <tr>
                                <th>Покрытие:</th>
                                <td id="viewCoverage"></td>
                            </tr>
                            <tr>
                                <th>Период:</th>
                                <td id="viewPeriod"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <h6 class="mt-4">Платежи</h6>
                <div class="table-responsive">
                    <table class="table table-sm" id="paymentsTable">
                        <thead>
                            <tr>
                                <th>Дата</th>
                                <th>Сумма</th>
                                <th>Тип</th>
                                <th>Статус</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
$(document).ready(function() {
    // Инициализация DataTable
    const table = $('#contractsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/api/v1/contracts',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('access_token')
            },
            dataSrc: 'data.data'
        },
        columns: [
            { data: 'id' },
            { 
                data: 'type',
                render: function(data) {
                    const types = {
                        'quota': 'Квотный',
                        'excess': 'Эксцедент',
                        'facultative': 'Факультативный'
                    };
                    return types[data] || data;
                }
            },
            { 
                data: 'reinsurer',
                render: function(data) {
                    return data ? data.name : '';
                }
            },
            { 
                data: 'coverage',
                render: function(data) {
                    return '₽' + new Intl.NumberFormat('ru-RU').format(data);
                }
            },
            { 
                data: 'status',
                render: function(data) {
                    const statuses = {
                        'active': ['Активен', 'success'],
                        'pending': ['На рассмотрении', 'warning'],
                        'canceled': ['Отменен', 'danger']
                    };
                    const [text, cls] = statuses[data] || [data, 'secondary'];
                    return `<span class="badge bg-${cls}">${text}</span>`;
                }
            },
            { 
                data: 'created_at',
                render: function(data) {
                    return new Date(data).toLocaleDateString('ru-RU');
                }
            }
        ],
        order: [[0, 'desc']]
    });

    // Загрузка перестраховщиков для формы
    function loadReinsurers() {
        $.ajax({
            url: '/api/v1/contracts',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('access_token')
            },
            success: function(response) {
                const select = $('#reinsurer_id');
                select.empty().append('<option value="">Выберите компанию</option>');
                response.reinsurers.forEach(reinsurer => {
                    select.append(`<option value="${reinsurer.id}">${reinsurer.name}</option>`);
                });
            }
        });
    }

    // Открытие модалки для создания
    $('#newContractBtn').click(function() {
        $('#contractId').val('');
        $('#contractForm')[0].reset();
        $('#modalTitle').text('Новый договор');
        loadReinsurers();
        $('#contractModal').modal('show');
    });

    // Отправка формы
    $('#contractForm').submit(function(e) {
        e.preventDefault();
        
        const formData = {
            type: $('#type').val(),
            reinsurer_id: $('#reinsurer_id').val(),
            premium: $('#premium').val(),
            coverage: $('#coverage').val(),
            start_date: $('#start_date').val(),
            end_date: $('#end_date').val()
        };

        const contractId = $('#contractId').val();
        const method = contractId ? 'PUT' : 'POST';
        const url = contractId ? `/api/v1/contracts/${contractId}` : '/api/v1/contracts';

        $.ajax({
            url: url,
            type: method,
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('access_token')
            },
            data: formData,
            success: function(response) {
                $('#contractModal').modal('hide');
                table.ajax.reload();
                showToast('success', response.message);
            },
            error: function(xhr) {
                showToast('error', xhr.responseJSON?.message || 'Ошибка');
            }
        });
    });

    // Просмотр договора
    $('#contractsTable tbody').on('click', 'tr', function() {
        const data = table.row(this).data();
        if (!data) return;

        $('#viewContractId').text(data.id);
        $('#viewType').text({
            'quota': 'Квотный',
            'excess': 'Эксцедент',
            'facultative': 'Факультативный'
        }[data.type]);
        $('#viewReinsurer').text(data.reinsurer?.name || '');
        $('#viewPremium').text('₽' + new Intl.NumberFormat('ru-RU').format(data.premium));
        $('#viewCoverage').text('₽' + new Intl.NumberFormat('ru-RU').format(data.coverage));
        $('#viewPeriod').text(
            new Date(data.start_date).toLocaleDateString('ru-RU') + ' - ' +
            new Date(data.end_date).toLocaleDateString('ru-RU')
        );
        
        const statusBadge = {
            'active': ['Активен', 'success'],
            'pending': ['На рассмотрении', 'warning'],
            'canceled': ['Отменен', 'danger']
        }[data.status];
        $('#viewStatus').text(statusBadge[0]).removeClass().addClass('badge bg-' + statusBadge[1]);

        // Загрузка платежей
        $.ajax({
            url: `/api/v1/contracts/${data.id}`,
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('access_token')
            },
            success: function(response) {
                const paymentsTable = $('#paymentsTable tbody');
                paymentsTable.empty();
                
                if (response.data.payments && response.data.payments.length > 0) {
                    response.data.payments.forEach(payment => {
                        paymentsTable.append(`
                            <tr>
                                <td>${new Date(payment.payment_date).toLocaleDateString('ru-RU')}</td>
                                <td>₽${new Intl.NumberFormat('ru-RU').format(payment.amount)}</td>
                                <td>${payment.type === 'premium' ? 'Премия' : 'Убыток'}</td>
                                <td><span class="badge bg-${payment.status === 'paid' ? 'success' : 'warning'}">
                                    ${payment.status === 'paid' ? 'Оплачен' : 'Ожидает'}
                                </span></td>
                            </tr>
                        `);
                    });
                } else {
                    paymentsTable.append('<tr><td colspan="4" class="text-center">Нет платежей</td></tr>');
                }
            }
        });

        $('#viewContractModal').modal('show');
    });

    // Вспомогательная функция для уведомлений
    function showToast(type, message) {
        const toast = `<div class="toast align-items-center text-white bg-${type} border-0 position-fixed bottom-0 end-0 m-3" role="alert">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>`;
        
        $(toast).appendTo('body').toast({ autohide: true, delay: 3000 }).toast('show');
    }
});
</script>
@endpush