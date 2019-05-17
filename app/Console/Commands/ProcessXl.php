<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\SP\Import;

class ProcessXl extends Command
{ 
    protected $signature = 'process:xl {filepath} {family} {month_year}';
    protected $description = 'To process excel sheet';

    public function __construct()
    {
        parent::__construct();
    }
   
    public function handle()
    {
        $filepath = $this->argument('filepath');
        $family = $this->argument('family');
        $month_year = $this->argument('month_year');

        try{
            $this->output->title('Starting import');
            $import = new Import($filepath, $family, $month_year);
            $import->handler();
            $this->output->success('Import successful');          
        }
        catch(\Exception $e)
        {
            $this->info($e->getMessage());
        }
    }
}
