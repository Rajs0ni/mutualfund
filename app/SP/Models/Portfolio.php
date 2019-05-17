<?php

namespace App\SP\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
   protected $fillable = [
       'mf_id',
       'month_year',
       'stock_id',
       'quantity',
       'mf_house'
   ];


    public function scopeAnalyzedRecords($query, $monthYear)
    {
        $data =  \DB::table('portfolios')
                ->select(\DB::raw("stock_id, count(mf_id) as mf_count, count(DISTINCT(mf_house)) as mfh_count, SUM(quantity) as q_sum "))
                ->where('month_year', 'Jan,2018')
                ->groupBy('stock_id')
                ->get();

        return $data;
    }

    public function scopeGetMonthYear($query)
    {
        $monthYear = \DB::table('portfolios')
                     ->select('month_year')->distinct()->get();
        return  $monthYear;
    }
}
