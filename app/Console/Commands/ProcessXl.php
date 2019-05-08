<?php

namespace App\Console\Commands;

use App\Imports\ExcelImport;
use Illuminate\Console\Command;
use Excel;
use App\Fund;

class ProcessXl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:xl {filepath}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To process excel sheet';

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
        
        $filepath = $this->argument('filepath');

        try{
            $uploadedFile = new \Symfony\Component\HttpFoundation\File\File($filepath);
            $allowed =  array('xlsx');
            $ext = $uploadedFile->getExtension();
            if(!in_array($ext, $allowed) ) {
                throw new \Exception('Invalid file. Only .xls/.xlsx files are allowed');
            }

            $this->output->title('Starting import');
            $Import = new ExcelImport();
            
            $ts = Excel::import($Import, $uploadedFile->getRealPath());

            // Return an import object for every sheet
            // $data = [];
            // foreach ($Import->getSheetNames() as $index => $sheetName) {
            //     $data[$index] = $sheetName;
            // }
            // dd($data);

            $sheetWithData = [];
            foreach ($Import->getSheetData() as $index => $value) {
                $sheetWithData[$index] = $value;
            }
        //  dd($sheetWithData["AXIS100"]);
        foreach ($sheetWithData["AXISGETF"] as $key => $row) {
         
            if($this->findHeadingRow($row))
            {
                $this->info("Process ahead ".$key);
                    
            }
        }



            // $parsedData = [];
            // foreach ( $sheetWithData as $key => $sheetData) {
            //     if($key == 'Index')
            //         continue;
            //     else
            //     {
            //         foreach ($sheetData as $key => $value) {
            //             if($value[""])
            //             {
            //                 $parsedData[] = $value;
            //             }
            //         }
            //     }              
            // }
            
            // foreach ($parsedData as $key => $record) {
            //     try{
            //        Fund::create([
            //            'Name of the Instrument' => $record["name_of_the_instrument"]  ?? null,
            //            'ISIN' => $record["isin"] ?? null,
            //            'Industry' => $record["rating"] ?? $record["industry"] ?? $record["industry_rating"] ?? null ,
            //            'Quantity' => $record["quantity"] ?? null,
            //            'Market/Fair' =>  $record["marketfair_value_rs_in_lakhs"] ?? null,
            //            '% to Net Assets' => $record["to_net_assets"] ?? null
            //        ]);
            //     }catch(\Exception $e){
            //         $this->info($e->getMessage());
            //     }
            // }

            $this->output->success('Import successful');
                
        }
        catch(\Exception $e)
        {
            $this->info($e->getMessage());
        }
    }
    
    public function findHeadingRow($row)
    {
            $result = 0;
            $flag =0;
            foreach ($row as $key => $value) { 
              if($value == "Name of the Instrument" || $value == "ISIN" ||  $value == "Quantity" )
                {
                    $flag = 1;
                   
                }
                else 
                {
                   $flag = 0;
                   
                }
                $result = $result || $flag;
            }
            return  $result;
     
    }
}
