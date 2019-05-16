<?php

namespace App\SP\Models;

use Illuminate\Database\Eloquent\Model;

class Analysis extends Model
{
    protected $fillable = [
        'stock_id',
        'month_year',
        'mf_increase',
        'mf_decrease',
        'mf_removed',
        'mf_new',
        'mfh_increase',
        'mfh_decrease',
        'mfh_removed',
        'mfh_new',
    ];
}
