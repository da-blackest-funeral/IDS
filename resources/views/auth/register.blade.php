@extends('layouts.app')
@section('title', 'Registration')
@section('content')
    <div class="form-wrapper">
        <div class="form-card">
            <form action="/register" method="POST" class="form form-control bg-primary h-100 rounded">
                @csrf
                <div class="form__header"> <!-- Надо центрировать -->
                    <h1>Регистрация</h1>
                </div>
                <div class="m-3 mx-auto">
                    <label for="name">Имя</label>
                    <input name="name" type="text" id="name" placeholder="Имя" class="form-control p-1">
                </div>
                <div class="m-3 mx-auto">
                    <label for="email">Логин</label>
                    <input name="email" type="email" id="email" placeholder="email" class="form-control p-1">
                </div>
                <div class="m-3 mx-auto">
                    <label for="password">Пароль</label>
                    <input name="password"
                           type="password"
                           class="form-control p-1"
                           placeholder="Пароль"
                           id="password">
                </div>
                <div class="m-3 mx-auto">
                    <label for="password_conformation">Подтвердите пароль</label>
                    <input name="password_confirmation"
                           type="password"
                           class="form-control p-1"
                           placeholder="Пароль"
                           id="password">
                </div>
{{--                todo изменить <a> на инпут изменить стиль кнопки и вставить такую же в логин--}}
                <div class="form-inline my-2 my-lg-0">
                    <a class="btn btn-outline-info my-2 my-sm-0" href="/login">Регистрация</a>
                </div>
            </form>
        </div>
    </div>
@endsection
