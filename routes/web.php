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
    
  //  try {
  //   \Artisan::call("process:xl", [
  //       'filepath' => "/home/raj/Downloads/Axis/Feb 2018 1973.xls",
  //       'family' =>"Axis",
  //       'month_year' => "Feb,2018"
  //   ]);
    
  //  }catch(\Exception $e)
  //  {
  //    return ($e->getMessage());
  //  }
    return view('welcome');

});
