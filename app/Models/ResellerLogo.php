<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResellerLogo extends Model
{
    use HasFactory;
    protected $fillable=['reseller_id', 'logo'];
}
