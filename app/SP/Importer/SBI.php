<?php

namespace App\SP\Importer;

use App\SP\Importer\Base;

class SBI extends Base{

    protected $indexFileName = ["Index"]; 
    protected $rowIndex = 2;
    protected $columnIndex = 3;

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
                $indexSheet[$shortname] = $sheet[$this->getRowIndex()][$this->getColumnIndex()];   
        }
        return $indexSheet;
    }

}