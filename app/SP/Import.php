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
        // dd($excelImport->getSheetNames());

        $sheets = [];
        foreach ($excelImport->getSheetData() as $index => $value) {
            $sheets[$index] = $value;
        }

        foreach ($sheets as $sheetSortname => $sheet) {
            if($instance->getIndexFileName() !== $sheetSortname )
            {   
                foreach (array_combine($instance->getSheetnameRowIndex(), $instance->getSheetnameColumnIndex()) as $rowIndex => $columnIndex) {
                    $sheetFullname = $sheet[$rowIndex][$columnIndex];
                    if($sheetFullname) break;
                }
                dd($sheetFullname); // $sheetFullname = $sheet[0][1];
                $instance->processEachSheet($sheet, $sheetSortname, $sheetFullname, $this->family, $this->month_year);
            }
                
        }
    }
}