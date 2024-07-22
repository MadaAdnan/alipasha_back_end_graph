<?php

namespace App\Observers;

use App\Models\Point;

class PointObserve
{
    /**
     * Handle the Point "created" event.
     */
    public function created(Point $point): void
    {
        //
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
