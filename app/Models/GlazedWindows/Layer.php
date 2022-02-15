<?php

namespace App\Models\GlazedWindows;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\GlazedWindows\Layer
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Layer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Layer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Layer query()
 * @mixin \Eloquent
 */
class Layer extends Model
{
    use HasFactory;

    protected $table = 'glazed_windows_layers';
}
