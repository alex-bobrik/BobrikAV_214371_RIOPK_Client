@extends('client.layouts.app')

@section('title', 'Редактирование договора')
@section('page-title', 'Редактирование договора перестрахования')

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/ru.js"></script>

<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: 'Выберите перестраховщика',
        width: '100%',
        language: 'ru'
    });

    $('#editContractForm').submit(function(e) {
        e.preventDefault();

        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        $('#submitText').addClass('d-none');
        $('#spinner').removeClass('d-none');
        $('#submitBtn').prop('disabled', true);

        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        const apiToken = localStorage.getItem('access_token') || getCookie('XSRF-TOKEN');

        const formData = {
            type: $('#type').val(),
            reinsurer_id: $('#reinsurer_id').val(),
            coverage: $('#coverage').val(),
            start_date: $('#start_date').val(),
            end_date: $('#end_date').val(),
            description: $('#description').val()
        };

        $.ajax({
            url: '/api/v1/contracts/' + {{ $contract->id }},
            type: 'PUT',
            headers: {
                'Authorization': 'Bearer ' + apiToken,
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            data: JSON.stringify(formData),
            success: function() {
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

    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }
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
        <h5 class="mb-0">Редактирование договора №{{ $contract->id }}</h5>
    </div>

    <div class="card-body">
        <form id="editContractForm">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="type" class="form-label">Тип договора *</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="quota" @selected($contract->type === 'quota')>Квотный</option>
                        <option value="excess" @selected($contract->type === 'excess')>Эксцедент</option>
                        <option value="facultative" @selected($contract->type === 'facultative')>Факультативный</option>
                    </select>
                    <div class="invalid-feedback" id="typeError"></div>
                </div>

                <div class="col-md-6">
                    <label for="reinsurer_id" class="form-label">Перестраховщик *</label>
                    <select class="form-select select2" id="reinsurer_id" name="reinsurer_id" required>
                        <option value="">Выберите перестраховщика</option>
                        @foreach($reinsurers as $reinsurer)
                            <option value="{{ $reinsurer->id }}" @selected($reinsurer->id === $contract->reinsurer_id)>
                                {{ $reinsurer->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback" id="reinsurer_idError"></div>
                </div>

                <div class="col-md-6">
                    <label for="coverage" class="form-label">Сумма покрытия (₽) *</label>
                    <input type="number" class="form-control" id="coverage" name="coverage" value="{{ $contract->coverage }}" required>
                    <div class="invalid-feedback" id="coverageError"></div>
                </div>

                <div class="col-md-3">
                    <label for="start_date" class="form-label">Дата начала *</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $contract->start_date }}" required>
                    <div class="invalid-feedback" id="start_dateError"></div>
                </div>

                <div class="col-md-3">
                    <label for="end_date" class="form-label">Дата окончания *</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $contract->end_date }}" required>
                    <div class="invalid-feedback" id="end_dateError"></div>
                </div>

            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <span id="submitText">Сохранить изменения</span>
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
                <h5 class="modal-title text-success">Договор обновлен</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <p>Изменения успешно сохранены.</p>
                <p>Номер договора: <strong>{{ $contract->id }}</strong></p>
            </div>
            <div class="modal-footer">
                <a href="{{ route('client.contracts.index') }}" class="btn btn-secondary">К списку договоров</a>
                <a href="{{ route('client.contracts.show', $contract->id) }}" class="btn btn-primary">Просмотреть договор</a>
            </div>
        </div>
    </div>
</div>
@endsection
