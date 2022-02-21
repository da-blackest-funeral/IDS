<?php

namespace App\Models\GlazedWindows;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\GlazedWindows\TemperatureController
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $amperage
 * @property string $temperature-range
 * @property float $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TemperatureController newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TemperatureController newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TemperatureController query()
 * @method static \Illuminate\Database\Eloquent\Builder|TemperatureController whereAmperage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemperatureController whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemperatureController whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemperatureController whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemperatureController whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemperatureController wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemperatureController whereTemperatureRange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TemperatureController whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TemperatureController extends Model
{
    use HasFactory;

    protected $table = 'temperature_controllers';
}
