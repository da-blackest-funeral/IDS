@extends('layouts.app')
@section('title', 'Registration')
@section('content')
    <div class="container">
        <section class="auth-form auth-form__register">
            <div class="mask d-flex align-items-center h-100">
                <div class="container h-100">
                    <div class="row d-flex justify-content-center align-items-center h-100">
                        <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                            <div class="card" style="border-radius: 15px;">
                                <div class="card-body p-5">
                                    <h2 class="text-uppercase text-center mb-5">Регистрация</h2>
                                    <form action="/register" method="POST">
                                        @csrf
                                        <div class="form-outline mb-4">
                                            <input
                                                class="form-control form-control-lg"
                                                name="name" type="text" id="name" placeholder="Имя"/>
                                            <label class="form-label" for="name">Имя</label>
                                        </div>

                                        <div class="form-outline mb-4">
                                            <input
                                                class="form-control form-control-lg"
                                                name="email" type="email" id="email" placeholder="E-mail"/>
                                            <label class="form-label" for="email">Логин</label>
                                        </div>

                                        <div class="form-outline mb-4">
                                            <input
                                                class="form-control form-control-lg"
                                                name="password"
                                                type="password"
                                                placeholder="Пароль"
                                                id="password"/>
                                            <label class="form-label" for="password">Пароль</label>
                                        </div>

                                        <div class="form-outline mb-4">
                                            <input name="password_confirmation"
                                                   type="password"
                                                   placeholder="Подтверждение"
                                                   id="password"
                                                   class="form-control form-control-lg"/>
                                            <label class="form-label" for="password">Повторите пароль</label>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <input name="register" class="btn btn-outline-primary btn-block btn-lg"
                                                   type="submit" value="Регистрация">
                                        </div>
                                        <p class="text-center text-muted mt-5 mb-0">Уже есть аккаунт?
                                            <a href="/login" class="fw-bold text-body"><u>Войдите здесь</u></a>
                                        </p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
