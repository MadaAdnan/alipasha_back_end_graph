<?php

namespace App\Console\Commands;

use App\Enums\PlansDurationEnum;
use App\Models\Plan;
use App\Models\User;
use App\Service\SendNotifyHelper;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpiredPlanJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expired:plan';

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
        $today = Carbon::today();

        try {
            $users = User::whereHas('plans', function ($query) use ($today) {
                $query->whereNot('duration', PlansDurationEnum::FREE->value);
                $query->whereDate('expired_date', '<=', $today);
            })->get();
            $data['title'] = 'تنبيه';
            $data['body'] = 'سينتهي إشتراكك في الخطة اليوم يرجى تجديد الإشتراك';

            SendNotifyHelper::sendNotifyMultiUser($users, $data);
        } catch (\Exception | \Error $e) {
        }

        try {
            $users = User::whereHas('plans', function ($query) use ($today) {
                $query->whereNot('duration', PlansDurationEnum::FREE->value);
                $query->whereDate('expired_date', '=', $today->addDays(5));
            })->get();
            $data['title'] = 'تنبيه';
            $data['body'] = 'سينتهي إشتراكك في الخطة بعد 5 أيام يرجى تجديد الإشتراك';

            SendNotifyHelper::sendNotifyMultiUser($users, $data);
        } catch (\Exception | \Error $e) {
        }

        try {
            $users = User::whereHas('plans', function ($query) use ($today) {
                $query->where('duration', PlansDurationEnum::FREE->value);
                $query->whereDate('expired_date', '=', $today->addDays(5));
            })->get();
            $plan = Plan::where('duration', PlansDurationEnum::FREE->value)->where('is_active', true)->first();
            $plan->users()->syncWithPivotValues($users, ['expired_date' => now()->addYear()], false);
            $data['title'] = 'تنبيه';
            $data['body'] = 'سينتهي إشتراكك في الخطة بعد 5 أيام يرجى تجديد الإشتراك';

            SendNotifyHelper::sendNotifyMultiUser($users, $data);
        } catch (\Exception | \Error $e) {
        }
    }
}
