<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\ImportDataEndpoints;

class ImportData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import-data:endpoints';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from endpoints';

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
        $importDataEndpoints = new ImportDataEndpoints();
        $importDataEndpoints->run(); 
 
        $this->info('Done!');
    }
}
