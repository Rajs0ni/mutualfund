<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class analyzeXl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analyze:stocks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To analyze mmutual fund stocks';

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
       
         
    }
}
