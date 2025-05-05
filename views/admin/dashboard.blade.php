@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Административная панель</div>

                <div class="card-body">
                    <h4>Добро пожаловать, {{ Auth::user()->name }}!</h4>
                    <p>Вы вошли как администратор системы.</p>
                    
                    <div class="mt-4">
                        <h5>Действия:</h5>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <a href="#">Управление пользователями</a>
                            </li>
                            <li class="list-group-item">
                                <a href="#">Управление компаниями</a>
                            </li>
                            <li class="list-group-item">
                                <a href="#">Просмотр всех договоров</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection