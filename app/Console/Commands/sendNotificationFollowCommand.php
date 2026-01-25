<?php

namespace App\Console\Commands;

use App\Jobs\SendFirebaseNotificationJob;
use App\Jobs\SendNotificationJob;
use App\Models\User;
use App\Models\UserFollow;
use App\Service\SendNotifyHelper;
use DB;
use Illuminate\Console\Command;

class sendNotificationFollowCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:follow';

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
        $followers = UserFollow::whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])
            ->select('seller_id', DB::raw('MIN(id) as first_follow_id'), DB::raw('count(*) as follower_count'))
            ->groupBy('seller_id')
            ->get();

        foreach ($followers as $follow) {

            // استرجاع أول متابع
            $firstFollower = UserFollow::find($follow->first_follow_id);
            $notify_user = User::find($firstFollower->user_id); // استرجاع المستخدم الأول الذي تابع

            $seller = User::find($follow->seller_id); // افتراض أن البائع هو المستخدم
            $follower_count = $follow->follower_count;

            if ($seller && $notify_user) {
                $data = [
                    'title' => 'متابعات جديدة',
                    'body' => 'يتابعك ' . $notify_user->name . ' و ' . ($follower_count - 1) . ' آخرون اليوم.',
                    'url' => 'https://ali-pasha.com/followers?seller_id=' . $seller->id,
                ];
                // إرسال الإشعار
                SendNotifyHelper::sendNotify($seller, $data);


            }
        }

    }
}
