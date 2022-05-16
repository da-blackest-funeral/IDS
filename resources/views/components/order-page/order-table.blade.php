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
        <td class="text-center align-middle">
            <span @class([
              'text-danger h5' => orderHasSale()
            ])>
                {{ orderPrice() }}
            </span>
        </td>
        <td class="text-center align-middle">{{ $order->products_count }}</td>
        <td class="text-center align-middle">{{ formatPrice($order->measuring_price) ? : 'Бесплатно' }}</td>
        <td class="text-center align-middle">{{ $order->delivery }}</td>
        <td class="text-center align-middle">{{ OrderHelper::salaries() }}</td>
        <td class="text-center align-middle">{{ carbon($order->created_at, 'd.m.Y') }}</td>
    </tr>
    </tbody>
</table>
