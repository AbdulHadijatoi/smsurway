<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsmNetwork extends Model
{
    use HasFactory;
    protected $fillable = [
        'network_name',
        'network_price',
        'user_id',
    ];
}
