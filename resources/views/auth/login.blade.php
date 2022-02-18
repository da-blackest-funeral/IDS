@extends('layouts.app')
@section('title', 'Вход')
@section('content')
    <div class="container">
        <form action="/login" method="POST" class="form-control bg-primary w-50 h-100 rounded">
            @csrf
            <div class="mx-auto"> <!-- Надо центрировать -->
                <h1>Вход</h1>
            </div>
            <div class="w-50 m-3 mx-auto">
                <label for="email">Логин</label>
                <input name="email" type="email" id="email" placeholder="email" class="form-control p-1">
            </div>
            <div class="w-50 m-3 mx-auto">
                <label for="password">Пароль</label>
                <input name="password"
                       type="password"
                       class="form-control p-1"
                       placeholder="Пароль"
                       id="password">
            </div>
            <div class="w-50 m-3 mx-auto">
                <input name="register" class="btn-success bordered" type="submit" value="Войти">
            </div>
            <div class="p-1">
                Забыли пароль?
            </div>
        </form>
    </div>
@endsection
