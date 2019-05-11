<?php

namespace App\SP;
use Excel;
use App\Imports\ExcelImport;

class Import {
    
    protected $filePath;
    protected $family;

    public function __construct($filepath, $family)
    {
        $this->filePath = $filepath;
        $this->family = $family;
    }


    public function handler()
    {
        $uploadedFile = new \Symfony\Component\HttpFoundation\File\File($this->filePath);
        $allowed =  array('xls','xlsx');
        $ext = $uploadedFile->getExtension();
        if(!in_array($ext, $allowed) ) {
            throw new \Exception('Invalid file. Only .xls/.xlsx files are allowed');
        }

        $family = "App\\SP\\Importer\\".$this->family;

        if(!class_exists($family , true))
            throw new \Exception($this->family." Family Not Found");
        
        $instance = new $family;
        $excelImport = new ExcelImport();
        Excel::import($excelImport, $uploadedFile->getRealPath());

        $sheets = [];
        foreach ($excelImport->getSheetData() as $index => $value) {
            $sheets[$index] = $value;
        }

        foreach ($sheets as $sheetSortname => $sheet) {
            if($instance->getIndexFileName() !== $sheetSortname )
            {   
                $sheetFullname = $sheet[$instance->getSheetnameRowIndex()][$instance->getSheetnameColumnIndex()];
                $instance->processEachSheet($sheet, $sheetSortname, $sheetFullname, $this->family);
            }
                
        }
    }
}