<?php

namespace App\SP\Importer;

use App\SP\Importer\Base;

class AdityaBirla extends Base{

    protected $indexFileName = ["Index"]; 
    protected $rowIndex = 0;
    protected $columnIndex = 0;

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
        $indexSheet = [];
        foreach ($sheets as $shortname => $sheet) {
            if(!in_array($shortname, $this->getIndexFileName()))
            {
                $fullname  = strlen($sheet[$this->getRowIndex()][$this->getColumnIndex()]) > strlen($shortname) ? 
                             $sheet[$this->getRowIndex()][$this->getColumnIndex()] :
                             $sheet[$this->getRowIndex()][$this->getColumnIndex()+1];                
                $indexSheet[$shortname] = $fullname;   
            }
        }
        return $indexSheet;
    }

}