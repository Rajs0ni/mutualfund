<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\SP\Models\Portfolio;

class analyzeXl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'analyze:stocks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To analyze mmutual fund stocks';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        try 
        {
            // $monthYear = 'Jan,2018';

            $monthYears =  Portfolio::GetMonthYear();
            dd($monthYears);
            $data = Portfolio::AnalyzedRecords($monthYear);
        //     $monthWiseRecords = \App\SP\Models\Portfolio::all()->groupBy('month_year');
        //     $stocks = \App\SP\Models\Stock::all();

        //     foreach ($stocks as $key => $stock) { 
                
        //         foreach ($monthWiseRecords as $monthYear => $records) 
        //         {
        //             // $this->processEachStock($stock,$records,$monthYear)
        //             $count = 0;
        //             $quantity = 0;
        //             foreach ($records as $key => $record) 
        //             {
        //                 if($stock->id == $record->stock_id)
        //                 {
        //                     $count++;
        //                     $quantity += $record->quantity;
        //                 }
        //             }

                   
        //             \App\SP\Models\AnalyzedRecord::create([
        //                 'stock_id' => $stock->id,
        //                 'month_year' => $monthYear,
        //                 'count' => $count,
        //                 'quantity' => $quantity,
        //                 'mfh' => NULL
        //             ]);
        //         }
        //    }

        }
        catch (\Exception $e) {
           $this->info($e->getMessage());
        }
        
    }


    public function processEachStock ($stock,$records,$monthYear){

        $count = 0;
        $quantity = 0;
        $mfHouse = [];
        foreach ($records as $key => $record) 
        {
            if($stock->id == $record->stock_id)
            {
                $count++;
                $quantity += $record->quantity;
                $mfHouse[] = $record->mf_house;
            }
        }
        $mfHouseCount = count(array_unique($mfHouse));
         // $this->save($stock,$count,$quantity,$monthYear)
    }

    public function save($stock,$count,$quantity,$monthYear,$mfHouseCount) {
        
        try 
        {
            \App\SP\Models\AnalyzedRecord::create([
                'stock_id' => $stock->id,
                'month_year' => $monthYear,
                'count' => $count,
                'quantity' => $quantity,
                'mfh' => $mfHouseCount
            ]);
        }
        catch (\Exception $e) {
            echo($e->getMessage());
        }
    }
}
