<?php

namespace App\SP\Importer;

use App\SP\Importer\Base;

class Axis extends Base {

    protected $indexFileName = ["Index"]; 
    protected $rowIndex = 1;
    protected $columnIndex = 2;

    public function getIndexFileName()
    {
        return $this->indexFileName;
    }

    public  function getRowIndex()
    {
        return $this->rowIndex;
    }

    public  function getColumnIndex()
    {
        return $this->columnIndex;
    }

    public function processIndexSheet($sheets,$sheetnames)
    {
        try 
        {
            $indexSheet = [];
            $filename = $this->isIndexFile($this->getIndexFileName(),$sheetnames);
            if($filename){
                for ($offset=$this->getRowIndex(); $offset < count($sheets[$filename]); $offset++)
                    $fullNames[] = $sheets[$filename][$offset][$this->getColumnIndex()];            
                $shortNames = array_splice($sheetnames,1);
                $indexSheet = array_combine($shortNames,$fullNames);
            }
            else
            {
                foreach ($sheets as $shortname => $sheet)
                    $indexSheet[$shortname] = $sheet[$this->getRowIndex()-1][$this->getColumnIndex()-1];   
            }
            return $indexSheet; 
        }
        catch(\Exception $e){
            echo($e->getMessage());
        }
                  
    }

    public function isIndexFile(array $indexFileNames, array $sheetnames)
    {
        foreach ($indexFileNames as $key => $filename) {
            foreach ($sheetnames as $key => $sheetname) {
                if(strtolower($filename) == strtolower($sheetname))
                return $filename;
            }
        }
        return NULL;
    }
}