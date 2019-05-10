<?php

namespace App\SP\Importer;

use Validator;
use App\SP\Models\Fund;

class Base {

    public $NAMEOFINSTRUMENT;
    public $ISIN;
    public $INDUSTRY;
    public $QUANTITY;
    public $MARKETVALUE;
    public $NAV;
    public $OrderedColumnHeader;

    public function processEachSheet($sheet) {

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
        $nav = ["net","assets","nav"];
        $headerIndex;

        try 
        {
            foreach ($sheet as $index => $row) {
                if($this->isHeaderRow($row))
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
                    return $index;
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
        $flag =0;
        $instrument = "Name of the Instrument";
        $isin = "ISIN";
        $quantity = "Quantity";
      
        foreach ($row as $key => $value) { 
            $flag = strtolower($value) == strtolower($instrument) ||
                    strtolower($value) == strtolower($isin) ||  
                    strtolower($value) == strtolower($quantity) ? 1 : 0;
            $result = $result || $flag;
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

    public function save($record){
        try{
            Fund::create([
                'Name of the Instrument' => $record[$this->NAMEOFINSTRUMENT], 
                'ISIN' => $record[$this->ISIN],
                'Industry' => $record[$this->INDUSTRY],
                'Quantity' => $record[$this->QUANTITY] ,
                'Market/Fair' =>  $record[$this->MARKETVALUE],
                '% to Net Assets' => $record[$this->NAV]
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