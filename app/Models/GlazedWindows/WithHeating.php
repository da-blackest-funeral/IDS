<?php

namespace App\Models\GlazedWindows;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\GlazedWindows\WithHeating
 *
 * @method static \Database\Factories\GlazedWindows\WithHeatingFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|WithHeating newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WithHeating newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WithHeating query()
 * @mixin \Eloquent
 * @property int $id
 * @property float $price
 * @property int $group_id
 * @property string $name
 * @property int $cameras
 * @property int $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\GlazedWindows\Group $group
 * @method static \Illuminate\Database\Eloquent\Builder|WithHeating whereCameras($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithHeating whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithHeating whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithHeating whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithHeating whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithHeating whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithHeating wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WithHeating whereUpdatedAt($value)
 */
class WithHeating extends Model
{
    use HasFactory;

    protected $table = 'glazed_windows_with_heating';

    public function group() {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }
}
