<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\AddUsersJob;

class AddUsersCommand extends Command
{
    protected $signature = 'users:add';
    protected $description = 'Add 1000 random user records';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        AddUsersJob::dispatch();
        $this->info('User adding job has been dispatched!');
    }
}
