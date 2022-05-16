<?php

namespace App\Models;

use App\Models\Salaries\InstallerSalary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property int $user_id
 * @property float $price
 * @property float $discount Цена со скидкой
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
 * @property float $measuring_price
 * @property bool $need_delivery
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductInOrder[] $products
 * @property InstallerSalary|null $salary
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereMeasuringPrice($value)
 * @property int $delivery
 * @property int $additional_visits
 * @property int $installation
 * @property int $installer_id
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereInstallation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereInstallerId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|InstallerSalary[] $salaries
 * @property-read int|null $salaries_count
 */
class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $hidden = [];
    protected $guarded = [];

    protected $casts = [
      'measuring_price' => 'integer'
    ];

    public function products() {
        return $this->hasMany(ProductInOrder::class);
    }

    public function salaries() {
        return $this->hasMany(InstallerSalary::class);
    }
}
