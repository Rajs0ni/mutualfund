<?php

namespace App\SP\Importer;

use Validator;
use App\SP\Models\Portfolio;
use App\SP\Models\MutualFund;
use App\SP\Models\Stock;

class Base {

    public $NAMEOFINSTRUMENT;
    public $ISIN;
    public $INDUSTRY;
    public $QUANTITY;
    public $MARKETVALUE;
    public $NAV;
    public $OrderedColumnHeader;
    public $family;
    public $sheetSortname;
    public $sheetFullname;
    public $month_year;
    public $mf_ID;

    public function processEachSheet($sheet, $sheetSortname, $sheetFullname, $family, $month_year) {

        $this->family = $family;
        $this->sheetSortname = $sheetSortname;
        $this->sheetFullname = $sheetFullname;
        $this->month_year = $month_year;
        $this->OrderedColumnHeader = [];
        $this->mf_ID = $this->saveFund();
        $header = $this->getHeaderRow($sheet);
        
        if($header)
        {
            for($index=($header+1); $index < count($sheet); $index++) 
            {
                $mappedRecord = $this->mapEachRecord($sheet[$index]);
                if($this->validateRecord($mappedRecord))
                    $this->save($mappedRecord);
            }
        }
    }

    public function getHeaderRow($sheet)
    {
        $headerKeywords = ["Name of the Instrument","ISIN","Quantity"];
        
        $nameofinst =["instrument","name"];
        $isin = ["isin"];
        $industry = ["industry", "rating"];
        $quantity = ["quantity"];
        $marketFair = ["market","fair","value"];
        $nav = ["net","assets","nav"];

        try 
        {
            foreach ($sheet as $rowIndex => $row) {
                if($this->isHeaderRow($row, $headerKeywords))
                {
                   
                    foreach ($row as $key => $value) {
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
                    // dd($this->OrderedColumnHeader);
                    return $rowIndex;
                }
            }
        }
        catch(\Exception $e){
           echo($e->getMessage());
        }
        
    }  
    
    public function isHeaderRow($row, $headerKeywords)
    {
        $result = 0;
        $flag = 0;
      
        foreach ($row as $key => $value) { 
            foreach ($headerKeywords as $keyword) {
                $flag = strtolower($value) == strtolower($keyword) ? 1 : 0;
                $result = $result || $flag;
            }
        }
        return $result;
    }

    public function mapEachRecord(array $record){
        try {
            
            foreach ($this->OrderedColumnHeader as $key => $value)
            {
                $mappedRecord[$value] = $record[$key];

            }
            return $mappedRecord;
        }   
        catch(\Exception $e){
            echo($e->getMessage());
        }
    }

    public function saveFund() {
        try {

            $mutualFund = MutualFund::create([
                'legal_id' => NULL,
                'nickname' => $this->sheetSortname,
                'name' => $this->sheetFullname,
                'family' => $this->family,

            ]);
            return $mutualFund->id;
        }
        catch(\Exception $e){
            echo($e->getMessage());
        }
    }

    public function save($record){
        try{
            $stock = Stock::create([
                'stock_name' => $record[$this->NAMEOFINSTRUMENT],
                'isin' => $record[$this->ISIN]
            ]); 

            if($stock && $this->mf_ID)
                Portfolio::create([
                    'stock_id' => $stock->id,
                    'mf_id' => $this->mf_ID,
                    'month_year' => $this->month_year,
                    'quantity' => $record[$this->QUANTITY]
                ]);
        }
        catch(\Exception $e){
            echo($e->getMessage());
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
                echo($e->getMessage());
            }
        }
    }
}