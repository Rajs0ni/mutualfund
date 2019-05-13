<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    
   try {
    \Artisan::call("test:xl", [
        'filepath' => "/home/raj/Downloads/Axis MF - Monthly Portfolios - March 2019.xls",
        'family' =>"Axis",
        'month_year' => "March,2019"
    ]);
    
   }catch(\Exception $e)
   {
     return ($e->getMessage());
   }
    // return view('welcome');
});
