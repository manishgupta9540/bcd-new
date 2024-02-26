<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\File;

class ZipUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zip:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears the Zip directory';

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
     * @return int
     */
    public function handle()
    {
        $path=public_path().'/zip/';
        if (File::exists($path)) 
        {
            File::cleanDirectory($path);
        }
        return 0;
    }
}
