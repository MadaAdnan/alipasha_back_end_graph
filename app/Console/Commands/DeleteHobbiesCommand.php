<?php

namespace App\Console\Commands;

use App\Models\Interaction;
use App\Models\Setting;
use Illuminate\Console\Command;

class DeleteHobbiesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:hobbies';

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
        $setting=Setting::first();
        Interaction::whereNull('seller_id')->where('created_at','<',now()->subDays($setting->options['recommended_delete']))->delete();
        Interaction::where('created_at','<',now()->subDays($setting->options['recommended_delete']))->delete();
    }
}
