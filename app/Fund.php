<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    protected $fillable = [
        'Name of the Instrument', 
        'ISIN', 
        'Industry', 
        'Quantity', 
        'Market/Fair', 
        '% to Net Assets'
    ];
}
