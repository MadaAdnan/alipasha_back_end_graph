<?php

namespace App\Observers;

use App\Events\CreateCommunityEvent;
use App\Models\Community;
use Mockery\Exception;

class CommunityObServe
{
    /**
     * Handle the Community "created" event.
     */
    public function created(Community $community): void
    {
        try{
            event(new CreateCommunityEvent($community));
        }catch (Exception | \Error $e){}

    }

    /**
     * Handle the Community "updated" event.
     */
    public function updated(Community $community): void
    {
        //
    }

    /**
     * Handle the Community "deleted" event.
     */
    public function deleted(Community $community): void
    {
        //
    }

    /**
     * Handle the Community "restored" event.
     */
    public function restored(Community $community): void
    {
        //
    }

    /**
     * Handle the Community "force deleted" event.
     */
    public function forceDeleted(Community $community): void
    {
        //
    }
}
