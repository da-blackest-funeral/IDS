<?php

namespace App\Models;

use App\Models\MosquitoSystems\Type;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Category
 *
 * @property int $id
 * @property string $name
 * @property int|null $parent_id Ссылка из подкатегории на супер-категорию
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Database\Factories\CategoryFactory factory(...$parameters)
 * @property-read Type|null $type
 * @method static \Illuminate\Database\Eloquent\Builder|Category tissues($id)
 */
class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
      'name',
      'parent_id'
    ];

    public function type() {
        return $this->hasOne(Type::class, 'category_id');
    }

    public function scopeTissues($query, $id) {
        $query->whereId($id)
            ->has('type.products.tissue')
            ->with('type.products.tissue');
    }
}
