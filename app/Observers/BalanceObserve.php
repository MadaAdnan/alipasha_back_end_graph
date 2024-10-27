<?php

namespace App\Observers;

use App\Models\Balance;
use App\Service\SendNotifyHelper;

class BalanceObserve
{
    /**
     * Handle the Balance "created" event.
     */
    public function creating(Balance $balance): void
    {
        $total = \DB::table('balances')->where('user_id', $balance->user_id)->selectRaw('SUM(credit)-SUM(debit) as total')->first()->total;
        $balance->total = ($total ?? 0) + ($balance->credit??0) - ($balance->debit ?? 0);
    }
    public function created(Balance $balance): void
    {
        if($balance->credit>0){
            $data['title'] = 'تنبيه';
            $data['body'] = 'تم شحن رصيدك بـ '.$balance->credit .'$';

            SendNotifyHelper::sendNotify($balance->user, $data);
        }elseif($balance->debit>0){
            $data['title'] = 'تنبيه';
            $data['body'] = "تم خصم {$balance->debit}  $ من رصيدك";

            SendNotifyHelper::sendNotify($balance->user, $data);
        }
    }
    /**
     * Handle the Balance "updated" event.
     */
    public function updated(Balance $balance): void
    {
        //
    }

    /**
     * Handle the Balance "deleted" event.
     */
    public function deleted(Balance $balance): void
    {
        //
    }

    /**
     * Handle the Balance "restored" event.
     */
    public function restored(Balance $balance): void
    {
        //
    }

    /**
     * Handle the Balance "force deleted" event.
     */
    public function forceDeleted(Balance $balance): void
    {
        //
    }
}
