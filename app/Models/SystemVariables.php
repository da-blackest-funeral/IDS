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
        return self::where('name', 'oneCamGlazedWindow')->first();
    }

    public static function twoCameraPrice() {
        return self::where('name', 'twoCamGlazedWindow')->first();
    }
}