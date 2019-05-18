<?php

namespace App\SP\Importer;

use App\SP\Importer\Base;

class HDFC extends Base{

    protected $indexFileName = ["Hyperlinks","Sheet1"]; 
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
                $indexSheet[$shortname] = $sheet[$this->getRowIndex()][$this->getColumnIndex()];   
        }
        return $indexSheet;
    }

}