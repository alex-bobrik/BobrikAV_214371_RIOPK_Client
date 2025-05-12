@extends('client.layouts.app')

@section('title', $company->name)
@section('page-title', 'Информация о компании')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <h4 class="mb-3 d-flex justify-content-between align-items-center">
            {{ $company->name }}
            @if (auth()->user()->company_id === $company->id)
                <span class="badge bg-primary">Текущая компания</span>
            @endif
        </h4>

        <p><strong>Страна:</strong> {{ $company->country }}</p>
        <p><strong>Тип:</strong>
            @switch($company->type)
                @case('insurer') Страховщик @break
                @case('reinsurer') Перестраховщик @break
                @default {{ $company->type }}
            @endswitch
        </p>
        <p><strong>Описание:</strong><br>{{ $company->description ?: '—' }}</p>
    </div>
</div>
@endsection
