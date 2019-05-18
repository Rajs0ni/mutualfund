<?php

namespace App\SP\Models;

use DB;
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

   public function scopeGetMonthYear($query)
    {
        $monthYear = DB::table('portfolios')
                     ->select('month_year')
                     ->distinct()
                     ->get();
        return  $monthYear;
    }

    public function scopeAnalyzedRecords($query,$monthYear)
    {
        $data =  DB::table('portfolios')
                ->select(DB::raw("stock_id, count(mf_id) as mf_count, count(DISTINCT(mf_house)) as mfh_count, SUM(quantity) as q_sum "))
                ->where('month_year', $monthYear)
                ->groupBy('stock_id')
                ->get();

        return $data;
    }

    public function scopeCheckRecordsExistFor($query,$monthYear,$family)
    {
      
        $records = DB::table('portfolios')
                ->where([
                    ['month_year',$monthYear],
                    ['mf_house',$family]
                ])
                ->get();
        return  $records->count();
    }

    public function scopeDeleteAll($query,$monthYear,$family)
    {
        DB::table('portfolios')
                ->where([
                    ['month_year',$monthYear],
                    ['mf_house',$family]
                ])
                ->delete();
    }
}
