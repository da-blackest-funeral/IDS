<?php

namespace App\Models\MosquitoSystems;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MosquitoSystems\Italian
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Italian newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Italian newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Italian query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $height
 * @property int $width
 * @property float $price Цена в долларах
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Italian whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Italian whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Italian whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Italian whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Italian wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Italian whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Italian whereWidth($value)
 */
class Italian extends Model
{
    use HasFactory;

    protected $table = 'mosquito_systems_italian';
}
