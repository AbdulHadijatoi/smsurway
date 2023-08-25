<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compaign extends Model
{
    use HasFactory;
    protected $fillable = [
        'to',
        'from',
        'msg',
        'msg_type',
        'msg_price',
        'msg_id',
        'limit',
        'user_id',
        'sendtime',
        'msg_count'
    ];
}
