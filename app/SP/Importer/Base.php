<?php

namespace App\SP\Importer;

use Validator;
use App\SP\Models\Portfolio;
use App\SP\Models\MutualFund;
use App\SP\Models\Stock;

class Base {

    public $indexSheet;
    public $NAMEOFINSTRUMENT;
    public $ISIN;
    public $INDUSTRY;
    public $QUANTITY;
    public $MARKETVALUE;
    public $NAV;
    public $OrderedColumnHeader;
    public $family;
    public $sheetSortname;
    public $month_year;
    public $mf_ID;

    public function setIndexSheet($indexSheet){
        $this->indexSheet = $indexSheet;
    }
    
    public function truncateExistingRecordsFor($month_year,$family)
    {
        try 
        {
            if(Portfolio::CheckRecordsExistFor($month_year,$family))
                Portfolio::DeleteAll($month_year,$family);
        }
        catch(\Exception $e){
            echo($e->getMessage());
        }
    }

    public function processEachSheet($sheet, $sheetSortname, $family, $month_year) {

        $this->family = $family;
        $this->sheetSortname = $sheetSortname;
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
        $nameofinst =["instrument","name"];
        $isin = ["isin"];
        $industry = ["industry", "rating"];
        $quantity = ["quantity"];
        $marketFair = ["market","fair","value"];
        $nav = ["net","assets","nav","aum"];

        try 
        {  
            foreach ($sheet as $rowIndex => $row) {
                if($this->isHeaderRow($row))
                {
                 
                    for ($offset=0; $offset < count($row) ; $offset++) { 
                        foreach ($nameofinst as $word) {
                            if (strpos(strtolower($row[$offset]), $word) !== FALSE) { 
                                $this->NAMEOFINSTRUMENT = $row[$offset];
                                $this->OrderedColumnHeader[$offset] = $row[$offset];
                                $offset++;
                                break;
                            }
                        }
                        foreach ($isin as $word) {
                            if (strpos(strtolower($row[$offset]), $word) !== FALSE) { 
                                $this->ISIN = $row[$offset];
                                $this->OrderedColumnHeader[$offset] = $row[$offset];
                                $offset++;
                                break;
                            }
                        }
                        foreach ($industry as $word) {
                            if (strpos(strtolower($row[$offset]), $word) !== FALSE) { 
                                $this->INDUSTRY = $row[$offset]; 
                                $this->OrderedColumnHeader[$offset] = $row[$offset];
                                $offset++;
                                break;
                            }
                        }
                        foreach ($quantity as $word) {
                            if (strpos(strtolower($row[$offset]), $word) !== FALSE) { 
                                $this->QUANTITY = $row[$offset]; 
                                $this->OrderedColumnHeader[$offset] = $row[$offset];
                                $offset++;
                                break;
                            }
                        }
                        foreach ($marketFair as $word) {
                            if (strpos(strtolower($row[$offset]), $word) !== FALSE) { 
                                $this->MARKETVALUE = $row[$offset]; 
                                $this->OrderedColumnHeader[$offset] = $row[$offset];
                                $offset++;
                                break;
                            }
                        }
                        foreach ($nav as $word) {
                            if (strpos(strtolower($row[$offset]), $word) !== FALSE) { 
                                $this->NAV = $row[$offset]; 
                                $this->OrderedColumnHeader[$offset] = $row[$offset];
                                $offset++;
                                break;
                            }
                        }
                    }
                    return $rowIndex;
                }
            }
        }
        catch(\Exception $e){
           echo($e->getMessage());
        }
        
    }  
    
    public function isHeaderRow($row)
    {
        $result = 0;
        $flag = 0;
        $headerKeywords = ["Name of the Instrument","ISIN","Quantity"];

        foreach ($row as $key => $value) { 
            foreach ($headerKeywords as $keyword) {
                $flag = strtolower($value) == strtolower($keyword) ? 1 : 0;
                $result = $result || $flag;
            }
        }
        return $result;
    }

    public function mapEachRecord(array $record){
        try
        {
            foreach ($this->OrderedColumnHeader as $key => $value)
                $mappedRecord[$value] = $record[$key];
            return $mappedRecord;
        }   
        catch(\Exception $e){
            echo($e->getMessage());
        }
    }

    public function saveFund() {
        try 
        {
            $mutualFund = MutualFund::where('nickname', trim($this->sheetSortname))->first();
            if(!$mutualFund)
            {
                $mutualFund = MutualFund::create([
                    'legal_id' => NULL,
                    'nickname' => $this->sheetSortname,
                    'name' => $this->indexSheet[$this->sheetSortname],
                    'family' => $this->family,
    
                ]);
            }
            return $mutualFund->id;
        }
        catch(\Exception $e){
            echo($e->getMessage());
        }
    }

    public function save($record){
        try
        {
            $stock = Stock::where('isin', trim($record[$this->ISIN]))->first();
            if(!$stock)
            {
                $stock = Stock::create([
                    'stock_name' => $record[$this->NAMEOFINSTRUMENT],
                    'isin' => $record[$this->ISIN]
                ]); 
            }

            if($stock && $this->mf_ID)
                Portfolio::create([
                    'stock_id' => $stock->id,
                    'mf_id' => $this->mf_ID,
                    'month_year' => $this->month_year,
                    'quantity' => $record[$this->QUANTITY],
                    'mf_house' => $this->family
                ]);
        }
        catch(\Exception $e){
            echo($e->getMessage());
        }
    }

    public function validateRecord($mappedRecord){
        if($mappedRecord){
            try 
            {
                $validator = Validator::make($mappedRecord, [
                    $this->NAMEOFINSTRUMENT => 'string',
                    $this->ISIN => 'required|alpha_num|max:12|min:12',
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