<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TypesWindows
 *
 * @property int $id
 * @property string $name
 * @property int $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\TypesWindowsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|TypesWindows newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TypesWindows newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TypesWindows query()
 * @method static \Illuminate\Database\Eloquent\Builder|TypesWindows whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypesWindows whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypesWindows whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypesWindows whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypesWindows whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TypesWindows extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sort',
    ];
}
