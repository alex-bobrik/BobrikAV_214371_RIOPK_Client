@extends('client.layouts.app')

@section('title', 'Создание договора')
@section('page-title', 'Новый договор перестрахования')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/ru.js"></script>

<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: 'Выберите компанию',
        width: '100%',
        language: 'ru'
    });

    $('#start_date').change(function() {
        const startDate = new Date($(this).val());
        if ($('#end_date').val()) {
            const endDate = new Date($('#end_date').val());
            if (endDate <= startDate) {
                $('#end_date').val('');
                $('#end_date').addClass('is-invalid');
                $('#endDateError').text('Дата окончания должна быть позже даты начала');
            }
        }
        $('#end_date').attr('min', $(this).val());
    });

    $('#createContractForm').submit(function(e) {
        e.preventDefault();
        
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        $('#submitText').addClass('d-none');
        $('#spinner').removeClass('d-none');
        $('#submitBtn').prop('disabled', true);
        
        const formData = {
            type: $('#type').val(),
            reinsurer_id: $('#reinsurer_id').val(),
            premium: $('#premium').val(),
            coverage: $('#coverage').val(),
            start_date: $('#start_date').val(),
            end_date: $('#end_date').val(),
            notes: $('#notes').val(),
            _token: '{{ csrf_token() }}'
        };
        
        $.ajax({
            url: '{{ route("client.contracts.store") }}',
            type: 'POST',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('access_token'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            data: JSON.stringify(formData),
            success: function(response) {
                $('#contractNumber').text('#' + response.data.id);
                $('#viewContractBtn').attr('href', '/client/contracts/' + response.data.id);
                $('#successModal').modal('show');
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    for (const field in errors) {
                        $(`#${field}`).addClass('is-invalid');
                        $(`#${field}Error`).text(errors[field][0]);
                    }
                } else {
                    alert(xhr.responseJSON?.message || 'Произошла ошибка');
                }
            },
            complete: function() {
                $('#submitText').removeClass('d-none');
                $('#spinner').addClass('d-none');
                $('#submitBtn').prop('disabled', false);
            }
        });
    });
});
</script>
@endpush

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single {
        height: 38px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
</style>
@endpush

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">Заполните данные договора</h5>
    </div>
    
    <div class="card-body">
        <form id="createContractForm">
            @csrf
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="type" class="form-label">Тип договора *</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="">Выберите тип</option>
                        <option value="quota">Квотный</option>
                        <option value="excess">Эксцедент</option>
                        <option value="facultative">Факультативный</option>
                    </select>
                    <div class="invalid-feedback" id="typeError"></div>
                </div>
                
                <div class="col-md-6">
                    <label for="reinsurer_id" class="form-label">Перестраховщик *</label>
                    <select class="form-select select2" id="reinsurer_id" name="reinsurer_id" required>
                        <option value="">Выберите компанию</option>
                        @foreach($reinsurers as $reinsurer)
                            <option value="{{ $reinsurer->id }}">{{ $reinsurer->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback" id="reinsurerError"></div>
                </div>
                
                <div class="col-md-6">
                    <label for="premium" class="form-label">Страховая премия (₽) *</label>
                    <input type="number" class="form-control" id="premium" name="premium" required>
                    <div class="invalid-feedback" id="premiumError"></div>
                </div>
                
                <div class="col-md-6">
                    <label for="coverage" class="form-label">Сумма покрытия (₽) *</label>
                    <input type="number" class="form-control" id="coverage" name="coverage" required>
                    <div class="invalid-feedback" id="coverageError"></div>
                </div>
                
                <div class="col-md-6">
                    <label for="start_date" class="form-label">Дата начала *</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                    <div class="invalid-feedback" id="startDateError"></div>
                </div>
                
                <div class="col-md-6">
                    <label for="end_date" class="form-label">Дата окончания *</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                    <div class="invalid-feedback" id="endDateError"></div>
                </div>
                
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <span id="submitText">Создать договор</span>
                    <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
                <a href="{{ route('client.contracts.index') }}" class="btn btn-secondary ms-2">Отмена</a>
            </div>
        </form>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-success">Договор создан</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Договор успешно создан и отправлен на рассмотрение.</p>
                <p>Номер договора: <strong id="contractNumber"></strong></p>
            </div>
            <div class="modal-footer">
                <a href="{{ route('client.contracts.index') }}" class="btn btn-secondary">К списку договоров</a>
                <a href="#" class="btn btn-primary" id="viewContractBtn">Просмотреть договор</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: 'Выберите компанию',
        width: '100%'
    });

    $('#start_date').change(function() {
        const startDate = new Date($(this).val());
        if ($('#end_date').val()) {
            const endDate = new Date($('#end_date').val());
            if (endDate <= startDate) {
                $('#end_date').val('');
                $('#end_date').addClass('is-invalid');
                $('#endDateError').text('Дата окончания должна быть позже даты начала');
            }
        }
        $('#end_date').attr('min', $(this).val());
    });

    $('#createContractForm').submit(function(e) {
    e.preventDefault();
    
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');
    
    $('#submitText').addClass('d-none');
    $('#spinner').removeClass('d-none');
    $('#submitBtn').prop('disabled', true);
    
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    const apiToken = localStorage.getItem('access_token') || getCookie('XSRF-TOKEN');
    const currentUser = @json(Auth::user());
    
    const formData = {
        type: $('#type').val(),
        reinsurer_id: $('#reinsurer_id').val(),
        premium: $('#premium').val(),
        coverage: $('#coverage').val(),
        start_date: $('#start_date').val(),
        end_date: $('#end_date').val(),
        notes: $('#notes').val(),
        user: currentUser,
    };
    
    $.ajax({
        url: '/api/v1/contracts',
        type: 'POST',
        headers: {
            'Authorization': 'Bearer ' + apiToken,
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        data: JSON.stringify(formData),
        success: function(response) {
            $('#contractNumber').text('#' + response.data.id);
            $('#viewContractBtn').attr('href', '/client/contracts/' + response.data.id);
            $('#successModal').modal('show');
        },
        error: function(xhr) {
            if (xhr.status === 401) {
                window.location.href = '/login';
            } else if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                for (const field in errors) {
                    $(`#${field}`).addClass('is-invalid');
                    $(`#${field}Error`).text(errors[field][0]);
                }
            } else {
                alert(xhr.responseJSON?.message || 'Произошла ошибка');
            }
        },
        complete: function() {
            $('#submitText').removeClass('d-none');
            $('#spinner').addClass('d-none');
            $('#submitBtn').prop('disabled', false);
        }
    });
});

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}
});
</script>
@endpush