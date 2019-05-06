<?php

namespace App\Imports;

use App\User;
use Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Imports\FirstSheetImport;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;


class UsersImport implements ToModel
{
    use Importable;
    
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // return new User([
        //     'id'     => $row[0],
        //     'name'     => $row[1],
        //     'email'    => $row[2], 
        //     'password' => Hash::make($row[3]),
        //  ]);
        // table($row);
    }

    // public function sheets(): array
    // {
    //     return [
    //         new FirstSheetImport()
    //     ];
    // }
}
