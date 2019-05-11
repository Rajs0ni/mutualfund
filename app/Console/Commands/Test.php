<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\SP\Import;

class Test extends Command
{
    protected $signature = 'test:xl {filepath} {family}';
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }
   
    public function handle()
    {
        $filepath = $this->argument('filepath');
        $family = $this->argument('family');

        try{
            $this->output->title('Starting import');
            $import = new Import($filepath, $family);
            $import->handler();
            $this->output->success('Import successful');          
        }
        catch(\Exception $e)
        {
            $this->info($e->getMessage());
        }
    }
}
