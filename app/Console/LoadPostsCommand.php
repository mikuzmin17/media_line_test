<?php

namespace App\Console;

use Illuminate\Console\Command;
use App\Jobs\ProcessParsing;

class LoadPostsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:parser {site?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command parsing posts from site';

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
        $site = $this->argument('site') ? $this->argument('site') : null;
        if ($site) ProcessParsing::dispatch($site);
        else ProcessParsing::dispatch();
    }
}
