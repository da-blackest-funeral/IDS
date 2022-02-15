<?php

namespace App\Models\Windowsills;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Windowsills\Windowsill
 *
 * @property int $id
 * @property string $name
 * @property int $category_id
 * @property int $material_color_id
 * @property float $plug_price Цена заглушки
 * @property float $price_docking_profile Цена стыковочного профиля
 * @property int $sort
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\Windowsills\WindowsillFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Windowsill newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Windowsill newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Windowsill query()
 * @method static \Illuminate\Database\Eloquent\Builder|Windowsill whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Windowsill whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Windowsill whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Windowsill whereMaterialColorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Windowsill whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Windowsill wherePlugPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Windowsill wherePriceDockingProfile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Windowsill whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Windowsill whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Windowsill whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Windowsill extends Model
{
    use HasFactory;
}
