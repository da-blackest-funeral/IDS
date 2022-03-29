@extends('layouts.app')
@section('title', 'Создание заказа')
@section('content')
    <div class="container mt-4" style="margin-bottom: 100px;">
        <h1 class="h1">
            Заказ <span style="font-size: 30px">№{{ $orderNumber }}</span>
        </h1>
        <div class="container-fluid mb-4" style="padding: 5px;">
            <a href="https://03-okna.ru/offer.php?num_rasch=34084" class="btn btn-secondary btn-sm">
                Комм.предл.
            </a>
            <a href="https://03-okna.ru/offer.php?num_rasch=34084&nosign=no" class="btn btn-secondary btn-sm">
                Комм.предл БП
            </a>
            <a href="https://03-okna.ru/invoice.php?num_rasch=34084" class="btn btn-secondary btn-sm">
                Накладная
            </a>
            <a href="https://03-okna.ru/act.php?num_rasch=34084" class="btn btn-secondary btn-sm">
                Акт
            </a>
            <a href="https://03-okna.ru/pko.php?num_rasch=34084" class="btn btn-secondary btn-sm">
                Чек ПКО
            </a>
            <a href="https://03-okna.ru/exel.php?num_rasch=34084" class="btn btn-secondary btn-sm">
                Stis excel
            </a>
            <a href="https://03-okna.ru/moedelo.php?num_rasch=34084" class="btn btn-secondary btn-sm">
                Счет моедело
            </a>
            <a href="/load.php?route=admin/calc/history&num_rasch=34084" class="btn btn-secondary btn-sm">
                Логи
            </a>
            <a href="/load.php?route=admin/master/sebestoimost&num_rasch=34084" class="btn btn-secondary btn-sm">
                Себестоимость
            </a>
            <a href="/load.php?route=admin/sklad/order&num_rasch=34084" class="btn btn-danger btn-sm">
                Создать списание
            </a>
        </div>

        @isset($products)
            {{--            @php--}}
            {{--            dump($order)--}}
            {{--            @endphp--}}
            <div class="row align-content-between">
                <div class="mt-4 w-75">
                    <h1 class="h2"><strong>Список товаров</strong></h1>
                    <div style="border-radius: 8px;overflow: hidden;">
                        <table class="table table-light table-hover table-bordered align-middle rounded">
                            <thead class="table-dark">
                            <tr>
                                <th scope="col" class="text-center">№</th>
                                <th scope="col" class="text-center">Название</th>
                                <th scope="col" class="text-center">Количество</th>
                                <th scope="col" class="text-center">Цена изделия</th>
                                <th scope="col" class="text-center">Дополнительно</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $product)
                                @php($productData = json_decode($product->data))
                                <tr>
                                    <th scope="row" class="text-center"><strong>{{ $loop->iteration }}</strong></th>
                                    <td class="text-center">{{ $product->name }}</td>
                                    <td class="text-center">{{ $product->count }}</td>
                                    <td class="text-center">{{ $productData->main_price }}</td>
                                    <td class="text-center">
                                        @foreach($productData->additional as $additional)
                                            <div class="p-1">
                                                {{ $additional }}
                                            </div>
                                        @endforeach
                                    </td>
                                    {{--                            <td class="text-center">{{ $productData->measuring > 0 ? $productData->measuring : 'Нет' }}</td>--}}
                                    {{--                            <td class="text-center">{{ $productData->salary }}</td>--}}
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="w-75 mt-4">
                    <h1 class="h2"><strong>Общие сведения о заказе</strong></h1>
                    <div style="border-radius: 8px;overflow: hidden;">
                        <table class="table table-bordered rounded" style="min-height: 130px;">
                            <thead>
                            <tr class="table-secondary">
                                <th scope="col" class="text-center">Замер</th>
                                <th scope="col" class="text-center">Заработок монтажника</th>
                                <th scope="col" class="text-center">Общая стоимость заказа</th>
                                <th class="text-center">Число товаров</th>
                                <th class="text-center">Создан</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center align-middle">{{ $order->measuring_price }}</td>
                                <td class="text-center align-middle">{{ $order->salary ?? 'Не готово' }}</td>
                                <td class="text-center align-middle">{{ $order->price }}</td>
                                <td class="text-center align-middle">{{ $order->products_count }}</td>
                                <td class="text-center align-middle">{{ \Carbon\Carbon::parse($order->created_at)->format('d.m.Y') }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{--        @dump(json_decode($productData->data ?? null))--}}
        <div class="mt-4">
            <h1 class="h3"><strong>Добавить товар</strong></h1>
            <div class="container-fluid bg-light" style="min-height: 250px;">
                <form action method="POST" class="form-group">
                    @csrf
                    <input type="hidden" value="{{ $orderNumber }}" name="order_id">
                    <div class="row">
                        <div class="col-12"></div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12 col-md-3">
                            <label class="mb-1 mt-2 mt-md-0" for="height">Высота (длина)</label>
                            <input name="height" id="height" placeholder="Высота (габаритн.) в мм." type="text"
                                   class="form-control" required>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="mb-1 mt-2 mt-md-0" for="width">Ширина (глубина)</label>
                            <input name="width" id="width" placeholder="Ширина (габаритн.) в мм." type="text"
                                   class="form-control" required>
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="mb-1 mt-2 mt-md-0" for="count">Количество (шт)</label>
                            <input name="count" id="count" placeholder="Количество (шт)" type="text"
                                   class="form-control"
                                   required>
                        </div>
                    </div>
                    <div class="row mt-4" id="items-row">
                        <div class="col-10 col-md-3 mt-2 mt-md-0" id="categories-container">
                            <label class="mb-1 mt-2 mt-md-0" for="categories">Тип изделия</label>
                            <select name="categories" id="categories" class="form-control">
                                <option>Тип изделия</option>
                                @foreach($superCategories as $category)
                                    <optgroup label="{{ $category->name }}">
                                        @foreach($data as $item)
                                            @if($item->parent_id == $category->id)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endif
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-10 col-md-3 mt-1" id="items">
                            {{-- Сюда грузится второй селект --}}
                        </div>
                        <div class="col-10 col-md-3 mt-1" id="third">
                            {{-- Место для третьего селекта --}}
                        </div>
                        <div class="col-10 col-md-3 mt-1" id="fourth">
                            {{-- Место для четвертого селекта --}}
                        </div>
                    </div>
                    <div class="mt-4 pb-3" id="additional">
                        {{-- Место для дополнительных опций --}}
                    </div>
                </form>
            </div>
        </div>
        <div class="mt-5">
            <h1 class="h3"><strong>Настройки всего заказа</strong></h1>
            <div class="bg-light p-3 pt-1">
                <form action="" method="post">
                    @csrf
                    <div class="mt-5">
                        <label class="btn btn-sm btn-secondary active">
                            <input type="radio" name="delivery" value="1" checked
                                   onclick="$('#delivery-options').show(400)">
                            <span style="font-weight: bold;">Доставка \ Выезд на монтаж</span>
                        </label>
                        <label class="btn btn-sm btn-secondary active">
                            <input type="radio" name="delivery" value="0" id="no_delivery"
                                   onchange="toggleDeliveryOptions()">
                            Самовывоз
                        </label>
                        <label class="btn btn-sm btn-secondary active">
                            <input type="radio" name="measuring" value="1" checked
                                   onclick="$('#delivery-options').show(400)">
                            <span style="font-weight: bold;">Нужен Замер</span>
                        </label>
                        <label class="btn btn-sm btn-secondary active">
                            <input type="radio" name="measuring" value="0" id="no_measuring"
                                   onchange="toggleDeliveryOptions()">
                            Без замера
                        </label>
                    </div>
                    <div id="delivery-options">
                        <div class="row mt-3 mb-3 w-75">
                            <div class="p-2 w-25">
                                <label>
                                    Количество доп. выездов
                                    <input type="text" value="0" name="count-additional-visits" class="form-control">
                                </label>
                            </div>
                            <div class="p-2 w-25">
                                <label>
                                    Км.
                                    <input type="text" value="0" name="kilometres" class="form-control">
                                </label>
                            </div>
                        </div>
                        <div class="mt-3 w-50">
                            <label for="address">Укажите точный адрес клиента</label>
                            <input type="text" class="form-control w-50" placeholder="Адрес клиента" name="address"
                                   id="address">
                        </div>
                    </div>
                    <div class="mt-3 w-50">
                        <label for="sale">Дополнительная скидка</label>
                        <select type="text" class="form-control w-50" name="sale" id="sale">
                            <option value="0">Без скидки</option>
                            <option value="5">Скидка 5%</option>
                        </select>
                    </div>
                    <div class="mt-3 w-50">
                        <label for="auto-sale">
                            Автоматическая скидка от цены заказа
                            @include('components.tooltip', ['tooltip' => 'Автоматическая скидка на некоторые категории товаров'])
                        </label>
                        <select class="form-control w-50" name="auto-sale" id="auto-sale">
                            <option value="0">Без скидки</option>
                            <option value="10">Скидка 10%</option>
                            <option value="15">Скидка 15%</option>
                        </select>
                    </div>
                    <div class="mt-3 w-50">
                        <label for="person">
                            Лицо
                            @include('components.tooltip', ['tooltip' => 'Если оплата производится по счету, то это юр. лицо'])
                        </label>
                        <select class="form-control w-50" name="person" id="person">
                            <option value="physical">Физ. лицо</option>
                            <option value="legal">Юр. лицо</option>
                        </select>
                    </div>
                    <div class="mt-3 w-50">
                        <label for="min-sum">
                            Минимальная сумма заказа
                        </label>
                        <input type="text" class="form-control w-50" name="min-sum" id="min-sum" value="5000">
                    </div>
                    <div class="mt-3 w-50">
                        <label for="sum-manually">
                            Ручное изменение суммы заказа
                        </label>
                        <input type="text" class="form-control w-50" name="sum-manually" id="sum-manually" value="0">
                    </div>
                    <div class="mt-3 w-50">
                        <label for="wage-manually">
                            Ручное изменение зарплаты
                            @include('components.tooltip', [
                                'tooltip' =>
                                '1. -1 - какой процент потерь из-за ручного изменения, такой процент и вычитается из зп.
        2. 0 - не вычитать ничего.
        3. любое число больше 0 - прибавить эту сумму.
        4. любое число меньше -1 - вычесть эту сумму.'
                            ])
                        </label>
                        <input type="text" class="form-control w-50" name="wage-manually" id="wage-manually" value="-1">
                    </div>
                    <div class="mt-3">
                        @include('components.calculations.submit')
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
