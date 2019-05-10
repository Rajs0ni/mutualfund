<?php

namespace App\SP;
use App\Imports\ExcelImport;

class Import {
    
    public $filePath;
    public $family;


    public function __construct($filepath, $family)
    {
        $this->filePath = $filepath;
        $this->family = $family;
    }


    public function handler()
    {
        try {
                $uploadedFile = new \Symfony\Component\HttpFoundation\File\File($this->filePath);
                $allowed =  array('xls','xlsx');
                $ext = $uploadedFile->getExtension();
                if(!in_array($ext, $allowed) ) {
                    throw new \Exception('Invalid file. Only .xls/.xlsx files are allowed');
                }

                $family = "App\\SP\\Importer\\".$this->family;

                if(!class_exists($family , true))
                    throw new \Exception("$family Family Not Found");
                
                $instance = new $family;
                $Import = new ExcelImport();
                    
                Excel::import($Import, $uploadedFile->getRealPath());
                $sheets = [];
                foreach ($Import->getSheetData() as $index => $value) {
                    $sheets[$index] = $value;
                }

                foreach ($sheets as $key => $sheet) {
                    $instance->processEachSheet($sheet);
                }

        }
        catch(\Exception $e)
        {
            echo($e->getMessage());
        }
    }
}