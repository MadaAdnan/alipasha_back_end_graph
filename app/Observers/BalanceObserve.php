<?php

namespace App\Observers;

use App\Models\Balance;

class BalanceObserve
{
    /**
     * Handle the Balance "created" event.
     */
    public function creating(Balance $balance): void
    {
        $total = \DB::table('balances')->where('user_id', $balance->user_id)->selectRaw('SUM(credit)-SUM(debit) as total')->first();
        $balance->total = $total ?? 0;
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
