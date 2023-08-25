<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;
    protected $fillable =[
        'user_id',
        'tx_id',
        'tx_ref',
        'token',
        'amount',
        'feeless_amount',
        'media',
        
    ];
    public function users(){
        return $this->hasOne(Transactions::class);
    }
    public function updateCredit($id){
        return $this->belongsTo(Transactions::class);
    }
    
}
