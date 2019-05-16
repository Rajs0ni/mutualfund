<?php

namespace App\SP\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
   protected $fillable = [
       'mf_id',
       'month_year',
       'stock_id',
       'quantity'
   ];

   public function stocks($monthYear)
   {
       return $monthYear;
   }

}
