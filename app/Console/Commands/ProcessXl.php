<?php

namespace App\Console\Commands;

use App\Imports\ExcelImport;
use Illuminate\Console\Command;
use Excel;

class ProcessXl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:xl {filepath}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To process excel sheet';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $filepath = $this->argument('filepath');

        try{
            $uploadedFile = new \Symfony\Component\HttpFoundation\File\File($filepath);
            $allowed =  array('xls','xlsx');
            $ext = $uploadedFile->getExtension();
            if(!in_array($ext, $allowed) ) {
                throw new \Exception('Invalid file. Only .xls/.xlsx files are allowed');
            }

            $this->output->title('Starting import');
            $Import = new ExcelImport();
            $ts = Excel::import($Import, $uploadedFile->getRealPath());
            // $data = [];
            // // Return an import object for every sheet
            // foreach ($Import->getSheetNames() as $index => $sheetName) {
            //     $data[$index] = $sheetName;
            // }
            $value = [];
       
            foreach ($Import->getSheetData() as $index => $sheet) {
           
                    $value[$index] = $sheet;
        
            }
            
            $this->output->success('Import successful');
       
        }
        catch(\Exception $e)
        {
            $this->info($e->getMessage());
        }
    }
}
