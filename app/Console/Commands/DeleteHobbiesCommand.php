<?php

namespace App\Console\Commands;

use App\Models\Interaction;
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
        Interaction::whereBetween('created_at',[now()->subDays(10),now()])->delete();
    }
}
