<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GsmPrefix extends Model
{
    use HasFactory;
    protected $fillable = [
        'network_name',
        'network_prefix',
    ];
    public function network(){
        return $this->hasMany(GsmNetwork::class);
    }
}
