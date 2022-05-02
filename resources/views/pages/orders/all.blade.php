@extends('layouts.app')
@section('title', 'Заказы')
@section('content')
    <div class="m-3">
        {{ $orders->links() }}
    </div>
    <div class="container">
        <h1 class="h2">Все заказы</h1>
        <div class="table-wrapper mt-3">
            <table class="table table-bordered table-light table-hover table-striped align-middle rounded">
                <thead class="table-dark">
                <tr class="text-center">
                    <th scope="col">Дата создания</th>
                    <th scope="col">Номер заказа</th>
                    <th scope="col">Создатель</th>
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
                        <td>{{ \Carbon\Carbon::make($order->created_at)->format('d.m.Y H:i:s') }}</td>
                        <td>{{ $order->id }}</td>
                        <td>{{ \App\Models\User::findOrFail($order->user_id)->name }}</td>
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
