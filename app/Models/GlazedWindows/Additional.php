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
 */
class Additional extends Model
{
    use HasFactory;

    protected $table = 'glazed_windows_additional';
}
