<?php

namespace App\SP;
use Excel;
use App\Imports\ExcelImport;

class Import {
    
    protected $filePath;
    protected $family;
    protected $month_year;

    public function __construct($filepath, $family, $month_year)
    {
        $this->filePath = $filepath;
        $this->family = $family;
        $this->month_year = $month_year;
    }


    public function handler()
    {
        
        try 
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
            
            $indexSheet = $instance->processIndexSheet($sheets,$excelImport->getSheetNames());
            $instance->setIndexSheet($indexSheet);

            foreach ($sheets as $sheetSortname => $sheet) {
                if(!in_array($sheetSortname, $instance->getIndexFileName()))
                    $instance->processEachSheet($sheet, $sheetSortname, $this->family, $this->month_year);    
            }
        } 
        catch(\Exception $e){
            echo($e->getMessage());
        }
    }
}