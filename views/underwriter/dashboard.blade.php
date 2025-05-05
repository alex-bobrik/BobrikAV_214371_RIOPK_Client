@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Панель андеррайтера</div>

                <div class="card-body">
                    <h4>Добро пожаловать, {{ Auth::user()->name }}!</h4>
                    <p>Вы вошли как андеррайтер компании {{ Auth::user()->company->name }}.</p>
                    
                    <div class="mt-4">
                        <h5>Действия:</h5>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <a href="#">Новые договоры</a>
                            </li>
                            <li class="list-group-item">
                                <a href="#">Текущие договоры</a>
                            </li>
                            <li class="list-group-item">
                                <a href="#">История платежей</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection