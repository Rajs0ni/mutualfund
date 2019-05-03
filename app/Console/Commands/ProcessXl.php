<?php

namespace App\Console\Commands;

use Validator;
use Illuminate\Console\Command;

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
        // $filepath = $this->argument('filepath');
        // try{

        //     $validator = Validator::make($request->all(), [
        //         'title' => 'required|unique:posts|max:255',
        //         'body' => 'required',
        //     ]);
    
        //     if ($validator->fails()) {
        //         return redirect('post/create')->withErrors($validator);
                    
                            
        //     $uploadedFile = new \Symfony\Component\HttpFoundation\File\File($filepath);
        //     // $file = file($filepath);
        //     $ext = $uploadedFile->getExtension();
        //     $this->info($ext);
        // }
        // catch(\Exception $e)
        // {
        //     $this->info($e->getMessage());
        // }
    }

}
