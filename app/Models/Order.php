<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property int $user_id
 * @property float $price
 * @property float $discounted_price Цена со скидкой
 * @property string $date
 * @property int $status Выполнен заказ или нет
 * @property int $measuring Нужен ли замер
 * @property float $discounted_measuring_price
 * @property string $comment
 * @property float $service_price Цена услуги
 * @property float $sum_after Спросить что это.
 * @property int $products_count Количество товаров
 * @property float $taken_sum Спросить что это.
 * @property float $installing_difficult Коэффициент сложности монтажа
 * @property int $is_private_person 1 - физическое лицо, 0 - юридическое.
 * @property int $done_status Статус завершения заказа
 * @property int $is_company_car Для доставки: была ли взята машина компании
 * @property float $prepayment Предоплата
 * @property int $installing_is_done
 * @property string $structure Текстовое описание всех составляющих заказа
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDiscountedMeasuringPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDiscountedPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDoneStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereInstallingDifficult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereInstallingIsDone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereIsCompanyCar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereIsPrivatePerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereMeasuring($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePrepayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereProductsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereServicePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStructure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereSumAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTakenSum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
    use HasFactory;
}
