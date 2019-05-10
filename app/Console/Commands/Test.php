<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\SP\Models\Fund;
use App\Imports\ExcelImport;
use App\SP\Importer\Base;
use App\SP\Import;
use Excel;
use Validator;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:xl {filepath, family}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $family = $this->argument('family');

        try{
            // $uploadedFile = new \Symfony\Component\HttpFoundation\File\File($filepath);
            // $allowed =  array('xls','xlsx');
            // $ext = $uploadedFile->getExtension();
            // if(!in_array($ext, $allowed) ) {
            //     throw new \Exception('Invalid file. Only .xls/.xlsx files are allowed');
            // }
            $this->output->title('Starting import');
            // $Import = new ExcelImport();
            
            // Excel::import($Import, $uploadedFile->getRealPath());

            // $sheets = [];
            // foreach ($Import->getSheetData() as $index => $value) {
            //     $sheets[$index] = $value;
            // }
            
            $object = new Import($filepath, $family);
            // foreach ($sheets as $key => $sheet) {
            //     $object->processEachSheet($sheet);
            // }

            $this->output->success('Import successful');          
        }
        catch(\Exception $e)
        {
            $this->info($e->getMessage());
        }
    }
}
