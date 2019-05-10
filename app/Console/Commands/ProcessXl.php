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
    public $QUANTITY;
    public $MARKETVALUE;
    public $NAV;
    public $OrderedColumnHeader;
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
                        $this->processEachSheet($sheet,$key);
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
        $QUANTITY = "Quantity";

        foreach ($row as $key => $value) { 
            $flag = strtolower($value) == strtolower($INSTRUMENT) ||
                    strtolower($value) == strtolower($ISIN) ||  
                    strtolower($value) == strtolower($QUANTITY) ? 1 : 0;
            $result = $result || $flag;
        }
        return $result;
    }

    public function processEachSheet($sheet, $headingRowIndex){
        
        try{

            $headerRow = $sheet[$headingRowIndex];
            $this->getOrderedHeader($headerRow);
            for($index=($headingRowIndex+1); $index < count($sheet); $index++) 
            {
                $mappedRecord = $this->mapRecord($sheet[$index]);
                if($this->validateRecord($mappedRecord))
                {
                    try{
                        Fund::create([
                            'Name of the Instrument' => $mappedRecord[$this->NAMEOFINSTRUMENT], 
                            'ISIN' => $mappedRecord[$this->ISIN],
                            'Industry' => $mappedRecord[$this->INDUSTRY],
                            'Quantity' => $mappedRecord[$this->QUANTITY] ,
                            'Market/Fair' =>  $mappedRecord[$this->MARKETVALUE],
                            '% to Net Assets' => $mappedRecord[$this->NAV]
                        ]);
                    }
                    catch(\Exception $e){
                        $this->info($e->getMessage());
                    }
                }
            }
        } 
        catch(\Exception $e){
            $this->info($e->getMessage());
        }
       

    }

    public function mapRecord(array $record){

        try {
            foreach ($this->OrderedColumnHeader as $key => $value) {
                $mappedRecord[$value] = $record[$key];
            }
            return $mappedRecord;
        }   
        catch(\Exception $e){
            $this->info($e->getMessage());
        }

        
    }
    
    public function getOrderedHeader(array $headerRow)
    {
        $nameofinst =["instrument","name"];
        $isin = ["isin"];
        $industry = ["industry", "rating"];
        $quantity = ["quantity"];
        $marketFair = ["market","fair","value"];
        $nav = ["net","assets","nav"];
        
        try {
            foreach ($headerRow as $key => $value) {
                foreach ($nameofinst as $word) {
                    if (strpos(strtolower($value), $word) !== FALSE) { 
                        $this->NAMEOFINSTRUMENT = $value;
                        $this->OrderedColumnHeader[$key] = $value;
                        break;
                    }
                }
                foreach ($isin as $word) {
                    if (strpos(strtolower($value), $word) !== FALSE) { 
                        $this->ISIN = $value;
                        $this->OrderedColumnHeader[$key] = $value;
                        break;
                    }
                }
                foreach ($industry as $word) {
                    if (strpos(strtolower($value), $word) !== FALSE) { 
                        $this->INDUSTRY = $value; 
                        $this->OrderedColumnHeader[$key] = $value;
                        break;
                    }
                }
                foreach ($quantity as $word) {
                    if (strpos(strtolower($value), $word) !== FALSE) { 
                        $this->QUANTITY = $value; 
                        $this->OrderedColumnHeader[$key] = $value;
                        break;
                    }
                }
                foreach ($marketFair as $word) {
                    if (strpos(strtolower($value), $word) !== FALSE) { 
                        $this->MARKETVALUE = $value; 
                        $this->OrderedColumnHeader[$key] = $value;
                        break;
                    }
                }
                foreach ($nav as $word) {
                    if (strpos(strtolower($value), $word) !== FALSE) { 
                        $this->NAV = $value; 
                        $this->OrderedColumnHeader[$key] = $value;
                        break;
                    }
                }
            }
        }
        catch(\Exception $e){
            $this->info($e->getMessage());
        }
    }

    public function validateRecord($mappedRecord){
        if($mappedRecord){
            try {
                $validator = Validator::make($mappedRecord, [
                    $this->NAMEOFINSTRUMENT => 'string',
                    $this->ISIN => 'required|alpha_num',
                    $this->QUANTITY => 'required|numeric',
                    $this->MARKETVALUE => 'numeric',   
                 ]);
         
                 if ($validator->passes()) {
                    return true;
                 }
            } 
            catch(\Exception $e){
                $this->info($e->getMessage());
            }
        }
    }

    public function containsSpecialLetter(array $record){
        $letter = "$";
        if(stripos($record["% to Net\n Assets"],$letter))
        $this->info(stripos($record["% to Net\n Assets"],$letter));
    }
   
}
