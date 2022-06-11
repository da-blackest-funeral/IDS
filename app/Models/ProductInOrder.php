<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\SoftDeletes;

    /**
     * App\Models\ProductinOrder
     *
     * @method static \Illuminate\Database\Eloquent\Builder|ProductInOrder newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ProductInOrder newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|ProductInOrder query()
     * @mixin \Eloquent
     * @property int $id
     * @property int $order_id
     * @property int $user_id
     * @property int $category_id
     * @property string $name
     * @property int $count
     * @property mixed $data Все дополнительные данные о заказе
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property string|null $deleted_at
     * @method static \Illuminate\Database\Eloquent\Builder|ProductInOrder whereCategoryId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ProductInOrder whereCount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ProductInOrder whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ProductInOrder whereData($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ProductInOrder whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ProductInOrder whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ProductInOrder whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ProductInOrder whereOrderId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ProductInOrder whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|ProductInOrder whereUserId($value)
     * @property int $installation_id
     * @method static \Illuminate\Database\Eloquent\Builder|ProductInOrder whereInstallationId($value)
     * @property-read \App\Models\Order|null $order
     * @property int $need_installation
     * @method static \Illuminate\Database\Eloquent\Builder|ProductInOrder whereNeedInstallation($value)
     */
    class ProductInOrder extends Model
    {
        use HasFactory, SoftDeletes;

        protected $table = 'products';

        protected $guarded = [];

        protected $casts = [
            'data' => 'object',
        ];

        public function order() {
            return $this->belongsTo(Order::class);
        }
    }
