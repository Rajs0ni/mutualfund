<?php

namespace App\SP\Importer;

use App\SP\Importer\Base;

class Axis extends Base {

    protected $indexFileName = "Index"; 
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

    public function processIndexFile($sheet){
        $temp=[];
        for ($offset=$instance->getRowIndex(); $offset < count($sheets[$instance->getIndexFileName()]); $offset++) { 

                    $temp[$offset] = $sheets[$instance->getIndexFileName()][$offset][$instance->getColumnIndex()];
                    
        }
        $t=[];
        foreach ($excelImport->getSheetNames() as $index => $sheetSortname) {
            if($instance->getIndexFileName() !== $sheetSortname)
            {
                $t[$sheetSortname] = $temp[$index];
            }
        }
    }
}