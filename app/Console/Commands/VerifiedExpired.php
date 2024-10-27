<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Service\SendNotifyHelper;
use Carbon\Carbon;
use Illuminate\Console\Command;

class VerifiedExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verified:expired';

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
        $users = User::where('is_verified', true)->whereDate('verified_account_date', $today)->get();

        try {
            $users->update(['is_verified' => false, 'verified_account_date' => null]);
            $data['title'] = 'تنبيه';
            $data['body'] = 'ينتهي اليوم إشتراك الحساب الموثق يرجى إعادة الإشتراك';

            SendNotifyHelper::sendNotifyMultiUser($users, $data);
        } catch (\Exception | \Error $e) {
        }

        $users = User::where('is_verified', true)->whereDate('verified_account_date', '=', $today->addDays(5))->get();

        try {

            $data['title'] = 'تنبيه';
            $data['body'] = 'سينتهي  إشتراك الحساب الموثق بعد 5 أيام يرجى إعادة الإشتراك';

            SendNotifyHelper::sendNotifyMultiUser($users, $data);
        } catch (\Exception | \Error $e) {
        }
    }
}
