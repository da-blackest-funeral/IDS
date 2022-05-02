@extends('layouts.app')
@section('title', 'Заказы')
@section('content')
    <div class="m-3">
        {{ $orders->links() }}
    </div>
    <div class="container">
        <div class="row">
            <div class="w-50">
                <h1 class="h2">Все заказы</h1>
            </div>
            <div style="max-width: 300px;">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" id="inputGroup-sizing-sm">Номер заказа</span>
                    <input type="text" class="form-control" id="order" aria-describedby="inputGroup-sizing-sm">
                </div>
            </div>
            <button type="button" onclick="window.location = '/orders/' + $('#order').val()"
                    class="btn btn-sm btn-success" style="width: 100px; height: 30px">
                Перейти
            </button>
        </div>
        <div class="table-wrapper mt-3">
            <table class="table table-bordered table-light table-hover table-striped align-middle rounded">
                <thead class="table-dark">
                <tr class="text-center">
                    <th scope="col">Дата создания</th>
                    <th scope="col">Номер заказа</th>
                    <th scope="col">Создал</th>
                    <th scope="col">Предоплата</th>
                    <th scope="col">Осталось</th>
                    <th scope="col">Статус</th>
                </tr>
                </thead>
                <tbody>
                @forelse($orders as $order)
                    <tr class="text-center" style="cursor: pointer" onclick="window.location = '{{
                        route('order', ['order' => $order->id])
                    }}'">
                        <td>{{ carbon($order->created_at, 'd.m.Y H:i:s') }}</td>
                        <td>{{ $order->id }}</td>
                        <td>{{ userName($order->user_id) }}</td>
                        <td>{{ 'Не готово' }}</td>
                        <td>{{ $order->price }} руб.</td>
                        <td>{{ 'Не готово' }}</td>
                    </tr>
                @empty
                    <h1 class="h3">Заказов пока нет.</h1>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
