<form action="" method="post">
    @method('put')
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
        <input type="text" class="form-control select-order" name="minimal-sum" id="min-sum" value="5000">
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
