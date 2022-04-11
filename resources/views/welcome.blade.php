@extends('layouts.app')
@section('title', 'Создание заказа')
@section('content')
    <div class="container mt-4" id="app" style="margin-bottom: 100px;">
        <h1 class="h1">
            Заказ <span style="font-size: 30px">№{{ $orderNumber }}</span>
        </h1>
        <app></app>
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
            @if(isOrderPage())
                <a href="/load.php?route=admin/sklad/order&num_rasch=34084" class="btn btn-success btn-sm">
                    Создать перемещение
                </a>
            @endif
        </div>

        @isset($products)
            {{-- todo Вова: колхозная кнопка --}}
            <a href="#" id="show" class="btn w-25" style="display: none;"
               onclick="$( this ).parent().children().show(400); $( this ).hide(400)">Развернуть
            </a>
            <div class="row align-content-between position-relative">
                @include('components.close', ['closeText' => 'Свернуть'])
                <div class="mt-4 w-75">
                    <h1 class="h2"><strong>Список товаров</strong></h1>
                    <div style="border-radius: 8px;overflow: hidden;">
                        {{-- todo Вова: надо сделать таблицу адаптивной --}}
                        <table class="table table-light table-hover table-bordered align-middle rounded">
                            <thead class="table-dark">
                            <tr>
                                <th scope="col" class="text-center">№</th>
                                <th scope="col" class="text-center" style="max-width: 100px;">Название</th>
                                <th scope="col" class="text-center">Размеры</th>
                                <th scope="col" class="text-center">Количество</th>
                                <th scope="col" class="text-center">Цена изделия</th>
                                <th scope="col" class="text-center">Дополнительно</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $product)
                                @php($productData = json_decode($product->data))
                                <tr style="cursor:pointer;" onclick="
                                {{-- todo Вова: вот таблица и вот как реализован переход на страницу редактирования товара --}}
                                    window.location ='{{ route('product-in-order', [
                                        'order' => $orderNumber,
                                        'productInOrder' => $product
                                        ])
                                    }}';">

                                    <th scope="row" class="text-center"><strong>{{ $loop->iteration }}</strong></th>
                                    <td class="text-center" style="max-width: 200px;">{{ $product->name }}</td>
                                    <td class="text-center text-success">
                                        <div>Высота: {{ $productData->size->height }} мм.</div>
                                        <br>
                                        <div>Ширина: {{ $productData->size->width }} мм.</div>
                                    </td>
                                    <td class="text-center"><strong>{{ $product->count }} шт.</strong></td>
                                    <td class="text-center">
                                        <strong>{{ $productData->main_price }}</strong>
                                        @if($product->count > 1)
                                            <p class="mt-3 ml-3">
                                                <em>{{ ceil($productData->main_price / $product->count) }} за
                                                    шт.</em></p>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @foreach($productData->additional as $additional)
                                            <div class="p-1 text-decoration-underline">
                                                {{ $additional->text }}
                                            </div>
                                            @if($product->count > 1 && $additional->price)
                                                <p class="mt-1">
                                                    <em>({{ ceil($additional->price / $product->count) }} за
                                                        шт.)</em></p>
                                            @endif
                                        @endforeach
                                        @isset($productData->salaryForCoefficient)
                                            <div class="p-1">
                                                <strong>{{ $productData->salaryForCoefficient }}</strong>
                                            </div>
                                        @endisset
                                        {{-- todo сделать вывод поля "Итого", Вова: тут же будет кнопка "Удалить" --}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
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
                                <td class="text-center align-middle">{{ $order->price }}</td>
                                <td class="text-center align-middle">{{ $order->products_count }}</td>
                                <td class="text-center align-middle">{{ $order->measuring_price }}</td>
                                <td class="text-center align-middle">{{ $order->delivery }}</td>
                                <td class="text-center align-middle">{{ $order->salaries()->get()->sum('sum') ?? 'Не готово' }}</td>
                                <td class="text-center align-middle">{{ \Carbon\Carbon::parse($order->created_at)->format('d.m.Y') }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @include('pages.add-product-form', ['needPreload' => !isset($order)])
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
