<?php

namespace App\Models\Other;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slope extends Model
{
    use HasFactory;

    protected $table = 'slopes';

    protected $guarded = ['id'];
}
