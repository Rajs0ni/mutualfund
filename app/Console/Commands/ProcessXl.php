<?php

namespace App\Console\Commands;

use Validator;
use App\Imports\UsersImport;
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
            (new UsersImport)->withOutput($this->output)->import($uploadedFile->getRealPath());
            $this->output->success('Import successful');
            // Excel::import(new UsersImport, $uploadedFile->getRealPath());
            // $data = Excel::toArray(new UsersImport,  $uploadedFile->getRealPath());
            // $data = (new UsersImport)->toArray($uploadedFile->getRealPath(),null, \Maatwebsite\Excel\Excel::XLSX);
           
          
       
        }
        catch(\Exception $e)
        {
            $this->info($e->getMessage());
        }
    }
}
