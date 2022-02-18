@extends('layouts.app')
@section('title', 'Registration')
@section('content')
    <div class="container">
            <form action="/register" method="POST" class="form-control bg-primary w-50 h-100 rounded">
                @csrf
                <div class="mx-auto"> <!-- Надо центрировать -->
                    <p>Test</p>
                </div>
                <div class="w-50 m-3 mx-auto">
                    <label for="name">Имя</label>
                    <input name="name" type="text" id="name" placeholder="Имя" class="form-control p-1">
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
                    <label for="password_conformation">Подтвердите пароль</label>
                    <input name="password_confirmation"
                           type="password"
                           class="form-control p-1"
                           placeholder="Подтверждение"
                           id="password">
                </div>
                <div class="w-50 m-3 mx-auto">
                    <input name="register" class="btn-success bordered" type="submit" value="Зарегистрироваться">
                </div>
            </form>
    </div>
@endsection
