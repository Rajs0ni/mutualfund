<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\SP\Models\Portfolio;
use App\SP\Models\AnalyzedRecord;

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
            $monthYears =  Portfolio::GetMonthYear();
    
            if($monthYears->count())
            {
                $this->output->title('Analyzing Records....');
                foreach ($monthYears as $key => $value) {
                    $records = Portfolio::AnalyzedRecords($value->month_year);
                    $this->storeEachRecord($value->month_year,$records);
                }
                $this->output->success("You're Done");
            }
        }
        catch (\Exception $e) {
           $this->info($e->getMessage());
        }
        
    }

    public function storeEachRecord($monthYear,$records)
    {
        try {
       
            foreach ($records as $key => $record) {

                $analyzedRecord = AnalyzedRecord::firstOrNew(
                    ['stock_id' => $record->stock_id , 'month_year' => $monthYear]
                );

                $analyzedRecord->stock_id = $record->stock_id;
                $analyzedRecord->month_year = $monthYear;
                $analyzedRecord->mf_count = $record->mf_count;
                $analyzedRecord->mfh_count = $record->mfh_count;
                $analyzedRecord->quantity = $record->q_sum;
                $analyzedRecord->save();
                // \App\SP\Models\AnalyzedRecord::create([
                //     'stock_id' => $record->stock_id,
                //     'month_year' => $monthYear,
                //     'mf_count' => $record->mf_count,
                //     'mfh_count' => $record->mfh_count,
                //     'quantity' => $record->q_sum
                // ]);
            }
        }
        catch (\Exception $e) {
            $this->info($e->getMessage());
         }
    }
    
}
