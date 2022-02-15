<?php

namespace App\Models\MosquitoSystems;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MosquitoSystems\Additional
 *
 * @property int $id
 * @property string $name
 * @property string|null $link
 * @property int $group_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\MosquitoSystems\AdditionalFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Additional newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Additional newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Additional query()
 * @method static \Illuminate\Database\Eloquent\Builder|Additional whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Additional whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Additional whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Additional whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Additional whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Additional whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MosquitoSystems\Type[] $additional
 * @property-read int|null $additional_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MosquitoSystems\Product[] $products
 * @property-read int|null $products_count
 */
class Additional extends Model
{
    use HasFactory;

    protected $table = 'mosquito_systems_additional';

    protected $fillable = [
        'name',
        'link',
        'group_id',
    ];

    public function products() {
        return $this->belongsToMany(
            Product::class,
            'mosquito_systems_product_additional',
            'additional_id',
            'product_id'
        );
    }

    public function types() {
        return $this->belongsToMany(
            Type::class,
            'mosquito_systems_type_additional',
            'additional_id',
            'type_id',
        );
    }

    public function group() {
        return $this->belongsTo(Group::class);
    }
}
