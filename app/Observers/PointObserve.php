<?php

namespace App\Observers;

use App\Models\Point;

class PointObserve
{
    /**
     * Handle the Point "created" event.
     */
    public function creating(Point $balance): void
    {
        $total = \DB::table('points')->where('user_id', $balance->user_id)->selectRaw('SUM(credit)-SUM(debit) as total')->first()->total;
        $balance->total = ($total ?? 0) + ($balance->credit??0) - ($balance->debit ?? 0);
    }

    /**
     * Handle the Point "updated" event.
     */
    public function updated(Point $point): void
    {
        //
    }

    /**
     * Handle the Point "deleted" event.
     */
    public function deleted(Point $point): void
    {
        //
    }

    /**
     * Handle the Point "restored" event.
     */
    public function restored(Point $point): void
    {
        //
    }

    /**
     * Handle the Point "force deleted" event.
     */
    public function forceDeleted(Point $point): void
    {
        //
    }
}
