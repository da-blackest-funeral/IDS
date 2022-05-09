@extends('layouts.app')
@section('title', 'Создание заказа')
@section('content')
    <div class="container mt-4" id="app" style="margin-bottom: 100px;">
        <h1 class="h1">
            Заказ <span style="font-size: 30px">№{{ $orderNumber }}</span>
        </h1>

        @include('components.order-page.top-section')

        @if(isset($products) && $products->isNotEmpty())
            {{-- todo Вова: колхозная кнопка --}}
            <a href="#" id="show" class="btn w-25" style="display: none;"
               onclick="$( this ).parent().children().show(400); $( this ).hide(400)">Развернуть
            </a>
            <div class="row align-content-between position-relative">
                @include('components.close', ['closeText' => 'Свернуть'])
                <div class="mt-4 w-75">
                    <h1 class="h2"><strong>Список товаров</strong></h1>
                    <div class="table-wrapper">
                        {{-- todo Вова: надо сделать таблицу адаптивной --}}
                        <x-products-table :products="$products"></x-products-table>
                    </div>
                </div>
                <div class="w-75 mt-4">
                    <h1 class="h2"><strong>Общие сведения о заказе</strong></h1>
                    <div style="border-radius: 8px;overflow: hidden;" class="position-relative">
                        <table class="table table-bordered rounded" style="min-height: 130px;">
                            <thead>
                            <tr class="table-secondary">
                                <th scope="col" class="text-center">Стоимость заказа</th>
                                <th class="text-center">Число товаров</th>
                                <th scope="col" class="text-center">Замер</th>
                                <th scope="col" class="text-center">Доставка</th>
                                <th scope="col" class="text-center">Заработок монтажника</th>
                                <th class="text-center">Создан</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center align-middle">{{ formatPrice($order->price) }}</td>
                                <td class="text-center align-middle">{{ $order->products_count }}</td>
                                <td class="text-center align-middle">{{ formatPrice($order->measuring_price) ? : 'Бесплатно' }}</td>
                                <td class="text-center align-middle">{{ $order->delivery }}</td>
                                <td class="text-center align-middle">{{ OrderHelper::salaries() }}</td>
                                <td class="text-center align-middle">{{ carbon($order->created_at, 'd.m.Y') }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @include('pages.add-product-form')
        @yield('add-product')

        <div class="mt-5">
            <h1 class="h3"><strong>Настройки всего заказа</strong></h1>
            <div class="bg-light p-3 pt-1">
                <form action="" method="post">
                    @csrf
                    <div class="mt-5">
                        <label class="btn btn-sm btn-secondary active">
                            {{-- todo Вова: тут тоже есть забавный таск позже расскажу ч тут д --}}
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
                        <div class="row mt-3 mb-3">
                            <div class="p-2 w-25 align-bottom">
                                <label>
                                    Количество доп. выездов
                                    <input type="text" value="0" name="count-additional-visits"
                                           class="form-control mw-100">
                                </label>
                            </div>
                            <div class="p-2 w-25 align-bottom">
                                <div>
                                    <label>
                                        Км.
                                        <input type="text" value="0" name="kilometres" class="form-control mw-100">
                                    </label>
                                </div>
                            </div>
                        </div>
                        @if(!isOrderPage())
                            <div class="mt-3 select-order">
                                <label for="address">Укажите точный адрес клиента</label>
                                <input type="text" class="form-control select-order" placeholder="Адрес клиента"
                                       name="address"
                                       id="address">
                            </div>
                        @endif
                    </div>
                    <div class="mt-3">
                        <label for="sale">Дополнительная скидка</label>
                        <select type="text" class="form-control select-order" name="sale" id="sale">
                            <option value="0">Без скидки</option>
                            <option value="5">Скидка 5%</option>
                        </select>
                    </div>
                    <div class="mt-3">
                        <label for="auto-sale">
                            Автоматическая скидка от цены заказа
                            @include('components.tooltip', ['tooltip' => 'Автоматическая скидка на некоторые категории товаров'])
                        </label>
                        <select class="form-control select-order" name="auto-sale" id="auto-sale">
                            <option value="0">Без скидки</option>
                            <option value="10">Скидка 10%</option>
                            <option value="15">Скидка 15%</option>
                        </select>
                    </div>
                    @if(isOrderPage())
                        @include('components.calculations.comment', [
                            'label' => 'Примечание ко всему заказу',
                            'name' => 'all-order-comment'
                        ])
                    @endif

                    @if(isOrderPage())
                        <div class="mt-3">
                            <label for="prepayment">
                                Предоплата
                                @include('components.tooltip', [
                                    'tooltip' => 'Во время замера вы взяли предоплату с клиента (вписывать только если это предоплата, а не оплата за заказ)'
                                ])
                            </label>
                            <input type="text" id="prepayment" name="prepayment" value="0"
                                   class="form-control select-order">
                        </div>
                    @endif

                    @if(isOrderPage())
                        @include('components.calculations.comment', [
                        'label' => 'Пожелание клиента',
                        'name' => 'wish'
                    ])
                    @endif

                    <div class="mt-3">
                        <label for="person">
                            Лицо
                            @include('components.tooltip', ['tooltip' => 'Если оплата производится по счету, то это юр. лицо'])
                        </label>
                        <select class="form-control select-order" name="person" id="person">
                            <option value="physical">Физ. лицо</option>
                            <option value="legal">Юр. лицо</option>
                        </select>
                    </div>
                    @if(isOrderPage())
                        <div class="mt-3">
                            <label for="installer">К какому монтажнику привязать заказ</label>
                            <select name="installer" id="installer" class="form-control select-order">
                                <option value="0">Ни к кому</option>
                                @isset($installers)
                                    @foreach($installers as $installer)
                                        <option value="{{ $installer->id }}">{{ $installer->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @endif
                    <div class="mt-3">
                        <label for="min-sum">
                            Минимальная сумма заказа
                        </label>
                        <input type="text" class="form-control select-order" name="min-sum" id="min-sum" value="5000">
                    </div>
                    <div class="mt-3">
                        <label for="sum-manually">
                            Ручное изменение суммы заказа
                        </label>
                        <input type="text" class="form-control select-order" name="sum-manually" id="sum-manually"
                               value="0">
                    </div>
                    <div class="mt-3">
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
                        <input type="text" class="form-control select-order" name="wage-manually" id="wage-manually"
                               value="-1">
                    </div>
                    <div class="mt-3">
                        @include('components.calculations.submit')
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
