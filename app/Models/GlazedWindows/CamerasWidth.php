<?php

namespace App\Models\GlazedWindows;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\GlazedWindows\CamerasWidth
 *
 * @method static \Illuminate\Database\Eloquent\Builder|CamerasWidth newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CamerasWidth newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CamerasWidth query()
 * @mixin \Eloquent
 * @property int $id
 * @property float $width
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\GlazedWindows\CamerasWidthFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|CamerasWidth whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CamerasWidth whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CamerasWidth whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CamerasWidth whereWidth($value)
 */
class CamerasWidth extends Model
{
    use HasFactory;

    protected $table = 'glazed_windows_cameras_width';
}
