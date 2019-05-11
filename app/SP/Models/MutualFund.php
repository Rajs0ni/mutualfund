<?php

namespace App\SP\Models;

use Illuminate\Database\Eloquent\Model;

class MutualFund extends Model
{
    protected $fillable = [
        'legal_id',
        'nickname',
        'name',
        'family'
    ];
}
