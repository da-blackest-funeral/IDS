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
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Layer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Layer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Layer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Layer whereUpdatedAt($value)
 */
class Layer extends Model
{
    use HasFactory;

    protected $table = 'glazed_windows_layers';
}
