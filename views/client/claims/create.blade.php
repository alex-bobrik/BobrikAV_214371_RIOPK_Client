@extends('client.layouts.app')

@section('title', 'Создание убытка')
@section('page-title', 'Новый страховой убыток')

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/ru.js"></script>

<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: 'Выберите договор',
        width: '100%',
        language: 'ru'
    });

    $('#createClaimForm').submit(function(e) {
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
            contract_id: $('#contract_id').val(),
            amount: $('#amount').val(),
            description: $('#description').val(),
            user: currentUser,
        };

        $.ajax({
            url: '/api/v1/claims',
            type: 'POST',
            headers: {
                'Authorization': 'Bearer ' + apiToken,
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            data: JSON.stringify(formData),
            success: function(response) {
                $('#claimId').text('#' + response.data.id);
                $('#viewClaimBtn').attr('href', '/client/claims/' + response.data.id);
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
        <h5 class="mb-0">Заполните данные убытка</h5>
    </div>

    <div class="card-body">
        <form id="createClaimForm">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="contract_id" class="form-label">Договор *</label>
                    <select class="form-select select2" id="contract_id" name="contract_id" required>
                        <option value="">Выберите договор</option>
                        @foreach($contracts as $contract)
                            <option value="{{ $contract->id }}">
                                №{{ $contract->id }} — {{ $contract->type }} ({{ $contract->reinsurer->name }})
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback" id="contract_idError"></div>
                </div>

                <div class="col-md-6">
                    <label for="amount" class="form-label">Сумма убытка (₽) *</label>
                    <input type="number" class="form-control" id="amount" name="amount" required>
                    <div class="invalid-feedback" id="amountError"></div>
                </div>

                <div class="col-12">
                    <label for="description" class="form-label">Описание *</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                    <div class="invalid-feedback" id="descriptionError"></div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <span id="submitText">Создать убыток</span>
                    <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
                <a href="{{ route('client.claims.index') }}" class="btn btn-secondary ms-2">Отмена</a>
            </div>
        </form>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-success">Убыток зарегистрирован</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <p>Убыток успешно создан и передан на рассмотрение.</p>
                <p>Номер убытка: <strong id="claimId"></strong></p>
            </div>
            <div class="modal-footer">
                <a href="{{ route('client.claims.index') }}" class="btn btn-secondary">К списку убытков</a>
                <a href="#" class="btn btn-primary" id="viewClaimBtn">Просмотреть убыток</a>
            </div>
        </div>
    </div>
</div>
@endsection
