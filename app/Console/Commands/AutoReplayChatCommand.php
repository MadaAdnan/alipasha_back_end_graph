<?php

namespace App\Console\Commands;

use App\Models\Community;
use App\Models\Message;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class AutoReplayChatCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'replay:chat';

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
        $start = Carbon::now()->subHours(72);
        $end = Carbon::now();  // الوقت الحالي

        $communities = DB::table('communities as c')
            ->join(DB::raw('(
        SELECT m1.community_id
        FROM messages m1
        JOIN messages m2 ON m1.user_id = m2.user_id
        JOIN messages m3 ON m1.user_id = m3.user_id
        WHERE m1.created_at < m2.created_at
        AND m2.created_at < m3.created_at
        AND m3.created_at BETWEEN ? AND ?
        GROUP BY m1.community_id
        HAVING COUNT(DISTINCT m1.user_id) = 1
        ORDER BY m3.created_at DESC
        LIMIT 1
    ) as sub'), 'c.id', '=', 'sub.community_id')
            ->where('c.type', '=', 'chat')  // شرط بأن يكون نوع المجتمع "chat"
            ->select('c.id')
            ->setBindings([$start, $end, 'chat'])  // تمرير المعاملات جميعها في الترتيب الصحيح
            ->pluck('id')->toArray();
        $communities = Community::whereIn('id', $communities)->get();
        \Log::info("COUNT:".$communities->count());
        /**
         * @var $item Community
         */
        foreach ($communities as $item) {
          $message= $item->messages()->latest()->first();
          if(now()->subHours(3)->greaterThan($message->created_at)){
              $user=$item->users()->where('users.id','!=',$message->user_id)->selectRaw('users.id,users.phone')->first();
              $msg = "مرجبا بك هذا رد تلقائي , يمكنك تنبيه التاجر بوجود محادثة جديدة معه في علي باشا عبر واتسآب من الرابط 👇\n
                        https://wa.me/" . trim('+',$user->phone) . "?text=مرحباً-هل-يمكنك-الرد-على-محادثتي-بتطبيق-علي-باشا";
              Message::create([
                  'community_id'=>$item->id,
                  'user_id'=>$user->id,
                  'type'=>'text',
                  'body'=>$msg
              ]);

          }

        }


    }
}
