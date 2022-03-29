<?php

namespace App\Models\Salaries;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Salaries\InstallerSalary
 *
 * @method static \Illuminate\Database\Eloquent\Builder|InstallerSalary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InstallerSalary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InstallerSalary query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id
 * @property int $order_id
 * @property float $sum
 * @property string $comment
 * @property int $status Спросить что это
 * @property float $changed_sum Спросить можно ли без этого
 * @property int $created_user_id
 * @property string $type Тип выплаты - за монтаж, за бензин и т.д.
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|InstallerSalary whereChangedSum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstallerSalary whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstallerSalary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstallerSalary whereCreatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstallerSalary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstallerSalary whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstallerSalary whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstallerSalary whereSum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstallerSalary whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstallerSalary whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstallerSalary whereUserId($value)
 */
class InstallerSalary extends Model
{
    use HasFactory;

    protected $table = 'installers_salaries';

    protected $guarded = [];
}
