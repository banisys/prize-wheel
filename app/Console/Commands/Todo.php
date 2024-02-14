<?php

namespace App\Console\Commands;

use App\Models\UserRequirement;
use Illuminate\Console\Command;

class Todo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'z:testing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        dd(now());
    }
}
