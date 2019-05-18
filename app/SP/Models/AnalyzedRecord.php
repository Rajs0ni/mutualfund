<?php

namespace App\SP\Models;

use Illuminate\Database\Eloquent\Model;
use CoenJacobs\EloquentCompositePrimaryKeys\HasCompositePrimaryKey;

class AnalyzedRecord extends Model
{
    use HasCompositePrimaryKey;
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = ['stock_id', 'month_year'];


    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    
    protected $fillable = [
        'stock_id',
        'month_year',
        'mf_count',
        'mfh_count',
        'quantity'
    ];
}
