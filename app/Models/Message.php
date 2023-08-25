<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = [
        'to',
        'from',
        'msg',
        'msg_type',
        'msg_price',
        'delivery_status',
        'msg_id',
        'limit',
        'user_id',
        'send_id',
        'sendtime',
        'msg_count'
    ];
}
