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
                <input type="hidden" value="{{ $orderNumber }}" name="order_id">
                @isset($product)
                    <input type="hidden" value="{{ $product->id }}" name="product_id">
                @endisset
                <div class="row mt-3">
                    <div class="col-12 col-md-3">
                        <label class="mb-1 mt-2 mt-md-0" for="height">Высота (длина)</label>
                        <input name="height"
                               id="height"
                               placeholder="Высота (габаритн.) в мм."
                               type="text"
                               class="form-control"
                               @if(needPreload())
                               value="{{ $product->data->size->height ?? '' }}"
                               @endif
                               required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="mb-1 mt-2 mt-md-0" for="width">Ширина (глубина)</label>
                        <input name="width"
                               id="width"
                               placeholder="Ширина (габаритн.) в мм."
                               type="text"
                               class="form-control"
                               @if(needPreload())
                               value="{{ $product->data->size->width ?? '' }}"
                               @endif
                               required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="mb-1 mt-2 mt-md-0" for="count">Количество (шт)</label>
                        <input name="count"
                               id="count"
                               placeholder="Количество (шт)"
                               type="text"
                               class="form-control"
                               @if(needPreload())
                               value="{{ $product->count ?? '' }}"
                               @endif
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
                                            <option
                                                @if(isset($product) && $product->category_id == $item->id && (needPreload()))
                                                selected
                                                @endif
                                                value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endif
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-10 col-md-3 mt-1" id="items">
                        {{-- Сюда грузится второй селект --}}
                        @if(isset($product) && needPreload())
                            @include('ajax.second-select', [
                            // todo переименовать метод в secondSelect
                                'data' => ProductHelper::tissues($product->category_id),
                                'link' => '/ajax/mosquito-systems/profile',
                                'name' => 'tissues',
                                'label' => 'Ткань',
                                'selected' => $product->data->tissueId
                            ])
                        @endif
                    </div>
                    <div class="col-10 col-md-3 mt-1" id="third">
                        {{-- Место для третьего селекта --}}
                        @if(isset($product) && needPreload())
                            @include('ajax.mosquito-systems.profiles', [
                            // todo переименовать метод в thirdSelect
                                'data' => ProductHelper::profiles($product),
                                'selected' => $product->data->profileId
                            ])
                        @endif
                    </div>
                    <div class="col-10 col-md-3 mt-1" id="fourth">
                        {{-- Место для четвертого селекта --}}
                    </div>
                </div>
                <div class="mt-4 pb-3" id="additional">
                    {{-- Место для дополнительных опций --}}
                    @if(isset($product) && needPreload())
                        @include('ajax.mosquito-systems.additional', ProductHelper::additional($product))
                    @endif
                </div>
                <div class="mt-4 pb-3" id="bracing">
                    {{-- Место для "добавить дополнительное крепление" --}}
                </div>
            </form>
        </div>
    </div>
@endsection
