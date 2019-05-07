<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Fund;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExcelImport implements ToArray, WithHeadingRow, WithEvents
{
    use Importable;
    /**
     * @param Collection $collection
     */
    public $sheetNames;
    public $sheetData;

    public function __construct(){
        $this->sheetNames = [];
        $this->sheetData = [];
    }
    public function array(array $array)
    {
        $this->sheetData[$this->sheetNames[count($this->sheetNames)-1]] = $array;
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $this->sheetNames[] = $event->getSheet()->getTitle();
            }
        ];
    }
    
    public function getSheetNames() {
        return $this->sheetNames;
    }

    public function getSheetData() {
        return $this->sheetData;
    }
   
   
    // public function model(array $row)
    // {
    //     return new Fund([
    //         'Name of the Instrument'  => $row['Name of the Instrument'] ?? $row['client'] ?? $row['name'] ?? null,
    //         'ISIN' => $row['ISIN'] ?? $row['client'] ?? $row['name'] ?? null,
    //         'Industry' => $row['Industry'] ?? $row['Rating'] ?? $row['Industry/Rating'] ?? null,
    //         'Quantity' => $row['Quantity'] ?? null,
    //         'Market/Fair' => $row['Market/Fair Value (Rs. in Lakhs)'] ?? null,
    //         '% to Net Assets' => $row['% to Net Assets'] ?? null
    //     ]);
    // }

    // public function sheets(): array
    // {
    //     return [
    //         new SheetImport()
    //     ];
    // }
  
}
