<?php

namespace App\Models\MosquitoSystems;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MosquitoSystems\Type
 *
 * @property int $id
 * @property string $name
 * @property string $yandex
 * @property string $page_link
 * @property string $measure_link Ссылка на страницу замера
 * @property float $salary Доп. зарплата монтажнику
 * @property float $price
 * @property string $description
 * @property string $img
 * @property int $measure_time Время замера в часах
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\MosquitoSystems\TypeFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Type newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Type newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Type query()
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereMeasureLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereMeasureTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type wherePageLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereYandex($value)
 * @mixin \Eloquent
 */
class Type extends Model
{
    use HasFactory;

    protected $table = 'mosquito_systems_types';

    protected $hidden = [
        'id'
    ];

    public function products() {
        return $this->hasMany(Product::class);
    }

    public function additional() {
        return $this->belongsToMany(
            Additional::class,
            'mosquito_systems_type_additional',
            'type_id',
            'additional_id'
        );
    }

    public function groups() {
        return $this->belongsToMany(
            Group::class,
            'mosquito_systems_type_group',
            'type_id',
            'group_id'
        );
    }
}
