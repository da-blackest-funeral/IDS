<?php

namespace App\Models\Slopes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Slopes\Slope
 *
 * @property int $id
 * @property string $name
 * @property int $sort
 * @property int $status
 * @property int $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Slope newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Slope newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Slope query()
 * @method static \Illuminate\Database\Eloquent\Builder|Slope whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slope whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slope whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slope whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slope whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slope whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slope whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Slope extends Model
{
    use HasFactory;
}
