<?php

namespace App\Console\Commands;

use App\Imports\ExcelImport;
use Illuminate\Console\Command;
use Excel;
use App\Fund;
use Validator;

class ProcessXl extends Command
{
    public $NAMEOFINSTRUMENT;
    public $ISIN;
    public $INDUSTRY;
    public $QUANTITIY;
    public $MARKETVALUE;
    public $NAV;
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
            $allowed =  array('xls','xlsx');
            $ext = $uploadedFile->getExtension();
            if(!in_array($ext, $allowed) ) {
                throw new \Exception('Invalid file. Only .xls/.xlsx files are allowed');
            }

            $this->output->title('Starting import');
            $Import = new ExcelImport();
            
            Excel::import($Import, $uploadedFile->getRealPath());

            $sheets = [];
            foreach ($Import->getSheetData() as $index => $value) {
                $sheets[$index] = $value;
            }
            foreach ($sheets as $key => $sheet) {
                foreach ($sheet as $key => $row) {
                    if($this->findHeadingRow($row))
                    {
                        $this->processRecord($sheet,$key);
                        break;
                    }
                }
            }
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
        $INSTRUMENT = "Name of the Instrument";
        $ISIN = "ISIN";
        $QUANTITIY = "Quantity";

        foreach ($row as $key => $value) { 
            $flag = strtolower($value) == strtolower($INSTRUMENT) ||
                    strtolower($value) == strtolower($ISIN) ||  
                    strtolower($value) == strtolower($QUANTITIY) ? 1 : 0;
            $result = $result || $flag;
        }
        return $result;
    }

    public function processRecord($sheet, $headingRowIndex){
        
        $headerRow = $sheet[$headingRowIndex];
        $orderedColumnHeader = $this->parseHeadingRow($headerRow);
        // $this->info("in process");
        // dd($orderedColumnHeader);
        $mappedRecord = [];
        for($index=($headingRowIndex+1); $index < count($sheet); $index++) {
            $mappedRecord[] = $this->mapRecord($sheet[$index]);
            // foreach ($headerRow as $key => $value) {
            //     $mappedRecord[$value] = $sheet[$index][$key];
            // }
            if($this->validateRecord($mappedRecord))
            {
                // try{
                //     Fund::create([
                //         'Name of the Instrument' => $mappedRecord["Name of the Instrument"], 
                //         'ISIN' => $mappedRecord["ISIN"],
                //         'Industry' => $mappedRecord["Rating"] ?? $mappedRecord["Industry"] ?? $mappedRecord["Industry / Rating"],
                //         'Quantity' => $mappedRecord["Quantity"] ,
                //         'Market/Fair' =>  $mappedRecord["Market/Fair Value\n (Rs. in Lakhs)"],
                //         '% to Net Assets' => $mappedRecord["% to Net\n Assets"]
                //     ]);
                //  }catch(\Exception $e){
                //      $this->info($e->getMessage());
                //  }
            }
        }
    }

    public function mapRecord(array $record){
        $mappedRecord = [];
        $mappedRecord[$this->NAMEOFINSTRUMENT] = $sheet[$index][$key];
    }
    
    public function parseHeadingRow(array $headerRow)
    {
        $nameofinst =["instrument","name"];
        $isin = ["isin"];
        $industry = ["industry", "rating"];
        $quantity = ["quantity"];
        $marketFair = ["market","fair","value"];
        $nav = ["net","assets","nav"];
        
        $orderedColumnHeader = [];
        foreach ($headerRow as $key => $value) {
            foreach ($nameofinst as $word) {
                if (strpos(strtolower($value), $word) !== FALSE) { 
                    $this->NAMEOFINSTRUMENT = $value;
                    $orderedColumnHeader[$key] = $value;
                    break;
                }
            }
            foreach ($isin as $word) {
                if (strpos(strtolower($value), $word) !== FALSE) { 
                    $this->ISIN = $value;
                    $orderedColumnHeader[$key] = $value;
                    break;
                }
            }
            foreach ($industry as $word) {
                if (strpos(strtolower($value), $word) !== FALSE) { 
                    $this->INDUSTRY = $value; 
                    $orderedColumnHeader[$key] = $value;
                    break;
                }
            }
            foreach ($quantity as $word) {
                if (strpos(strtolower($value), $word) !== FALSE) { 
                    $this->QUANTITIY = $value; 
                    $orderedColumnHeader[$key] = $value;
                    break;
                }
            }
            foreach ($marketFair as $word) {
                if (strpos(strtolower($value), $word) !== FALSE) { 
                    $this->MARKETVALUE = $value; 
                    $orderedColumnHeader[$key] = $value;
                    break;
                }
            }
            foreach ($nav as $word) {
                if (strpos(strtolower($value), $word) !== FALSE) { 
                    $this->NAV = $value; 
                    $orderedColumnHeader[$key] = $value;
                    break;
                }
            }
        }
        return $orderedColumnHeader;
    }

    public function validateRecord(array $mappedRecord){
        
        $validator = Validator::make($mappedRecord, [
           $this->n => 'string',
           $this->is => 'required|alpha_num',
           $this->q => 'required|numeric',
           $this->m => 'numeric'
        ]);

        if ($validator->passes()) {
           return true;
        }
    }

    public function containsSpecialLetter(array $record){
        $letter = "$";
        if(stripos($record["% to Net\n Assets"],$letter))
        $this->info(stripos($record["% to Net\n Assets"],$letter));
    }
   
}
