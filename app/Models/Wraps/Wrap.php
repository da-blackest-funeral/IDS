<?php

namespace App\Models\Wraps;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Wraps\Wrap
 *
 * @property int $id
 * @property string $name
 * @property string|null $url
 * @property string|null $img
 * @property int $calc_show
 * @property int $catalog_show
 * @property int $sort
 * @property string $description
 * @property int $wraps_service_id
 * @property int $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Wrap newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wrap newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wrap query()
 * @method static \Illuminate\Database\Eloquent\Builder|Wrap whereCalcShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wrap whereCatalogShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wrap whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wrap whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wrap whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wrap whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wrap whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wrap whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wrap whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wrap whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wrap whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wrap whereWrapsServiceId($value)
 * @mixin \Eloquent
 */
class Wrap extends Model
{
    use HasFactory;
}
