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
 */
class WithHeating extends Model
{
    use HasFactory;

    protected $table = 'glazed_windows_with_heating';
}
