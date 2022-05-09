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
            <x-order-table></x-order-table>
        </div>
    </div>
</div>
