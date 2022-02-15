<?php

namespace App\Models\Overlays;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Overlays\Overlay
 *
 * @property int $id
 * @property int $color_id
 * @property int $size_id
 * @property float $price
 * @property float $montage_price
 * @property int $service_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Overlay newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Overlay newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Overlay query()
 * @method static \Illuminate\Database\Eloquent\Builder|Overlay whereColorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overlay whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overlay whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overlay whereMontagePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overlay wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overlay whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overlay whereSizeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Overlay whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Overlay extends Model
{
    use HasFactory;
}
