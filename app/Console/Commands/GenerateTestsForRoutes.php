<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TestsService;

class GenerateTestsForRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:test-for-routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automated creation of test for all routes';

    private $tests;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TestsService $tests)
    {
        parent::__construct();
        $this->tests = $tests;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->tests->generate();
        return 0;
    }
}
