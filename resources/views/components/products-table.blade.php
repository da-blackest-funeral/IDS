<table class="table table-light table-hover table-bordered align-middle rounded">
    <thead class="table-dark">
    <tr>
        <th scope="col" class="text-center">№</th>
        <th scope="col" class="text-center" style="max-width: 100px;">Название</th>
        <th scope="col" class="text-center">Размеры</th>
        <th scope="col" class="text-center">Количество</th>
        <th scope="col" class="text-center">Цена изделия</th>
        <th scope="col" class="text-center">Дополнительно</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($products as $product)
        {{-- todo сделать компонент этой tr с онкликом, оборачивающий другой html --}}
        <tr style="cursor:pointer;" onclick="
        {{-- todo Вова: вот таблица и вот как реализован переход на страницу редактирования товара --}}
            window.location ='{{ route('product-in-order', [
                'order' => $order->id,
                'productInOrder' => $product->id
            ]) }}'">

            <th scope="row" class="text-center"><strong>{{ $loop->iteration }}</strong></th>
            <td class="text-center" style="max-width: 200px;">{{ $product->name }}</td>
            <td class="text-center text-success">
                <div>Высота: {{ $product->data->size->height }} мм.</div>
                <br>
                <div>Ширина: {{ $product->data->size->width }} мм.</div>
            </td>
            <td class="text-center"><strong>{{ $product->count }} шт.</strong></td>
            <td class="text-center">
                <strong>{{ $product->data->main_price }}</strong>
                @if($product->count > 1)
                    <p class="mt-3 ml-3">
                        <em>{{ ceil($product->data->main_price / $product->count) }} за
                            шт.</em></p>
                @endif
            </td>
            <td class="text-center">
                @foreach($product->data->additional as $additional)
                    <div class="p-1 text-decoration-underline">
                        {{ $additional->text }}
                    </div>
                    @if($product->count > 1 && $additional->price)
                        <p class="mt-1">
                            <em>({{ ceil($additional->price / $product->count) }} за
                                шт.)</em></p>
                    @endif
                @endforeach
                @isset($product->data->salaryForCoefficient)
                    <div class="p-1">
                        <strong>{{ $product->data->salaryForCoefficient }}</strong>
                    </div>
                @endisset
                {{-- todo сделать вывод поля "Итого", Вова: тут же будет кнопка "Удалить" --}}
            </td>
            <td class="text-center">
                @include('components.delete-button-form', [
                    'action' => route('product-in-order', [
                        'order' => $order->id,
                        'productInOrder' => $product->id
                    ])
                ])
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
