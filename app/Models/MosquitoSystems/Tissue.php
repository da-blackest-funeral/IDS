<?php

namespace App\Models\MosquitoSystems;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MosquitoSystems\Tissue
 *
 * @property int $id
 * @property string $name
 * @property string $link_page
 * @property string $description
 * @property float $cut_width Ширина отреза, м.
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\MosquitoSystems\TissueFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Tissue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tissue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tissue query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tissue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tissue whereCutWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tissue whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tissue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tissue whereLinkPage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tissue whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tissue whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MosquitoSystems\Product[] $products
 * @property-read int|null $products_count
 */
class Tissue extends Model
{
    use HasFactory;

    protected $table = 'mosquito_systems_tissues';

    protected $fillable = [
        'name',
        'link_page',
        'description',
        'cut_width'
    ];

    public function products() {
        return $this->hasMany(Product::class);
    }
}
