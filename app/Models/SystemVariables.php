<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SystemVariables
 *
 * @property int $id
 * @property string $name
 * @property string $value
 * @property string $description
 * @method static \Illuminate\Database\Eloquent\Builder|SystemVariables newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemVariables newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemVariables query()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemVariables whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemVariables whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemVariables whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SystemVariables whereValue($value)
 * @mixin \Eloquent
 */
class SystemVariables extends Model
{
    use HasFactory;

    public static function oneCameraPrice() {
        return self::whereName('oneCamGlazedWindow')->first();
    }

    public static function twoCameraPrice() {
        return self::whereName('twoCamGlazedWindow')->first();
    }

    public static function repairCoefficient() {
        return self::whereName('repairCoefficient')->first()->value;
    }

    public static function coefficientFastCreating() {
        return self::whereName('coefficientFastCreating')->first()->value;
    }

    public static function coefficientSalaryForDifficult() {
        return self::whereName('coefficientSalaryForDifficult')->first()->value;
    }
}
