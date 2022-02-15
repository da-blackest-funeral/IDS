<?php

namespace App\Models\GlazedWindows;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\GlazedWindows\Glass
 *
 * @property int $id
 * @property string $name
 * @property float $price
 * @property int $sort
 * @property string $thickness Толщина
 * @property int $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Glass newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Glass newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Glass query()
 * @method static \Illuminate\Database\Eloquent\Builder|Glass whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Glass whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Glass whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Glass whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Glass wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Glass whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Glass whereThickness($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Glass whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Glass extends Model
{
    use HasFactory;

    protected $table = 'glass';

    protected $fillable = [
        'name',
        'price',
        'sort',
        'category_id'
    ];
}
