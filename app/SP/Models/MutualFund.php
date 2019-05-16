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


    public function portfolios()
    {
        return $this->hasMany('App\SP\Models\Portfolio','mf_id');
    }
}
