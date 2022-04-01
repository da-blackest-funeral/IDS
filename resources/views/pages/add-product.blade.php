@extends('layouts.app')
@section('title', 'Редактирование товара')
@section('content')
    @include('pages.add-product-form')
    {{-- todo сделать чтобы менялся тайтл (редактировать товар), чтобы все селекты уже были выбраны и данные заполнены --}}
    <div class="container">
        @yield('add-product')
    </div>
@endsection
