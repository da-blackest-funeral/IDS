<?php

namespace App\Models\MosquitoSystems;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MosquitoSystems\Product
 *
 * @property int $id
 * @property int $type_id
 * @property int $tissue_id
 * @property int $profile_id
 * @property int $category_id
 * @property float $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\MosquitoSystems\Profile|null $profile
 * @property-read \App\Models\MosquitoSystems\Tissue|null $tissue
 * @property-read \App\Models\MosquitoSystems\Type|null $type
 * @method static \Database\Factories\MosquitoSystems\ProductFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTissueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MosquitoSystems\Additional[] $additional
 * @property-read int|null $additional_count
 */
class Product extends Model
{
    use HasFactory;

    protected $table = 'mosquito_systems_products';

//    protected $hidden = ['id'];

    public function tissue() {
        return $this->belongsTo(Tissue::class,'tissue_id','id');
    }

    public function type() {
        return $this->hasOne(Type::class, 'id', 'type_id');
    }

    public function profile() {
        return $this->hasOne(Profile::class, 'id', 'profile_id');
    }

    public function additional() {
        return $this->belongsToMany(
            Additional::class,
            'mosquito_systems_product_additional',
            'product_id',
            'additional_id'
        );
    }
}
