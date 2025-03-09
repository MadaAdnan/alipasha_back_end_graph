<?php

namespace App\Console\Commands;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Console\Command;

class RenewFreePlanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plan:renew-free';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'renew subscribe free plan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $plan=Plan::where('duration',\App\Enums\PlansDurationEnum::FREE->value)->first();
        if($plan!=null){
            $users = User::whereHas('plans', function ($query)use($plan) {
                $query->where('plans.id',$plan->id)->whereDate('plan_user.expired_date', '<', now()->addDays(5));
            })->pluck('id');
            $plan->users()->syncWithPivotValues($users,['expired_date'=>now()->addYear()],false);
        }

    }
}
