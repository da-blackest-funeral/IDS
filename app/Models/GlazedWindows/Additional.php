<?php

namespace App\Models\GlazedWindows;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\GlazedWindows\Additional
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Additional newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Additional newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Additional query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $value
 * @property int $sort
 * @property float $price
 * @property int $layer_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Additional whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Additional whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Additional whereLayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Additional whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Additional wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Additional whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Additional whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Additional whereValue($value)
 */
class Additional extends Model
{
    use HasFactory;

    protected $table = 'glazed_windows_additional';

    public function layer() {
        return $this->belongsTo(Layer::class, 'layer_id', 'id');
    }
}
