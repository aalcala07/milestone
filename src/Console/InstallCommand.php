<?php

namespace Milestone\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'milestone:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->callSilent('vendor:publish', ['--tag' => 'milestone-assets']);
        $this->callSilent('vendor:publish', ['--tag' => 'milestone-config']);
        $this->callSilent('migrate');

        $this->info('Installation complete.');
    }
    
}