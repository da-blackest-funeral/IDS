@extends('layouts.app')
@section('title', 'Редактирование товара')
@section('content')
    @include('pages.add-product-form', ['heading' => 'Редактировать товар'])
    <div class="container">
        @yield('add-product')
    </div>
@endsection
