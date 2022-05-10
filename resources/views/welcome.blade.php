@extends('layouts.app')
@section('title', 'Создание заказа')
@section('content')
    <div class="container mt-4" id="app" style="margin-bottom: 100px;">
        <h1 class="h1">
            Заказ <span style="font-size: 30px">№{{ $orderNumber }}</span>
        </h1>

        @include('components.order-page.top-section')

        @includeWhen(isset($products) && $products->isNotEmpty(), 'components.order-page.order-section')

        @include('pages.add-product-form')
        @yield('add-product')

        <div class="mt-5">
            <h1 class="h3"><strong>Настройки всего заказа</strong></h1>
            <div class="bg-light p-3 pt-1">
                @include('components.order-page.order-form')
            </div>
        </div>
    </div>
@endsection
