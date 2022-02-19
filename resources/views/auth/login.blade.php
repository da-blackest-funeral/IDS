@extends('layouts.app')
@section('title', 'Вход')
@section('content')
    <div class="form-wrapper">
        <div class="form-card">
            <form action="/login" method="POST" class="form login__form form-control bg-primary w-100 h-100 rounded">
                @csrf
                <div class="mx-auto"> <!-- Надо центрировать -->
                    <h1 class="form__header">Вход</h1>
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
                <div class="form-inline my-2 my-lg-0">
                    <a class="btn btn-outline-info my-2 my-sm-0" href="/login">Войти</a>
                </div>
                <div class="m-3 mx-auto">
                    <div class="p-1">
                        Забыли пароль?
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
