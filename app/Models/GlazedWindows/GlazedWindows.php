<?php

namespace App\Models\GlazedWindows;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\GlazedWindows\GlazedWindows
 *
 * @property int $id
 * @property int $layer_id
 * @property int $width_id
 * @property int $category_id
 * @property float $price
 * @property int $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\GlazedWindows\GlazedWindowsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|GlazedWindows newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GlazedWindows newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GlazedWindows query()
 * @method static \Illuminate\Database\Eloquent\Builder|GlazedWindows whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GlazedWindows whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GlazedWindows whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GlazedWindows whereLayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GlazedWindows wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GlazedWindows whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GlazedWindows whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GlazedWindows whereWidthId($value)
 * @mixin \Eloquent
 */
class GlazedWindows extends Model
{
    use HasFactory;
}
