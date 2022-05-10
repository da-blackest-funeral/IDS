@section('add-product')
    <div class="mt-4">
        <h1 class="h3">
            <strong>
                {{ $heading ?? 'Добавить товар' }} {{ isset($product->name) && !isOrderPage() ? "\"$product->name\"" : '' }}
            </strong>
        </h1>
        @if(!isOrderPage())
            <p class="h4 mt-3">Заказ №{{ $orderNumber }}</p>
        @endif
        <div class="container-fluid bg-light mt-4" style="min-height: 250px;">
            <form method="POST" class="form-group pt-1">
                @csrf
                <div class="row mt-3">
                    @include('components.order-page.main-form-select', [
                        'name' => 'height',
                        'label' => 'Высота (длина)',
                        'placeholder' => 'Высота (габаритн.) в мм.',
                        'value' => $product->data->size->height ?? ''
                    ])

                    @include('components.order-page.main-form-select', [
                        'name' => 'width',
                        'label' => 'Ширина (глубина)',
                        'placeholder' => 'Ширина (габаритн.) в мм.',
                        'value' => $product->data->size->width ?? ''
                    ])

                    @include('components.order-page.main-form-select', [
                        'name' => 'count',
                        'label' => 'Количество (шт)',
                        'value' => $product->count ?? ''
                    ])
                </div>
                <div class="row mt-4" id="items-row">

                    @include('components.order-page.categories')

                    <div class="col-10 col-md-3 mt-1" id="items">
                        {{-- Сюда грузится второй селект --}}
                        @if(requestHasProduct())
                            <x-second-select></x-second-select>
                        @endif
                    </div>
                    <div class="col-10 col-md-3 mt-1" id="third">
                        {{-- Место для третьего селекта --}}
                        @if(requestHasProduct())
                            <x-third-select></x-third-select>
                        @endif
                    </div>
                    <div class="col-10 col-md-3 mt-1" id="fourth">
                        {{-- Место для четвертого селекта --}}
                    </div>
                </div>
                <div class="mt-4 pb-3" id="additional">
                    {{-- Место для дополнительных опций --}}
                    @if(requestHasProduct())
                        <x-additional></x-additional>
                    @endif
                </div>
                <div class="mt-4 pb-3" id="bracing">
                    {{-- Место для "добавить дополнительное крепление" --}}
                </div>
            </form>
        </div>
    </div>
@endsection
