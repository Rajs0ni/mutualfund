<?php

namespace App\SP\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyzedRecord extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'stock_id',
        'month_year',
        'count',
        'quantity',
        'mfh'
        
    ];
}
