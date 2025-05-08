<?php

namespace App\Console\Commands;

use App\Jobs\ForceNotificationJob;
use Illuminate\Console\Command;

class ForceNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:force-notification';

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
        $job=new ForceNotificationJob();
        dispatch($job);
    }
}
