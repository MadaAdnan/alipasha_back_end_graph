<?php

namespace App\Observers;

use App\Events\ChangeSettingEvent;
use App\Models\Setting;
use Pusher\PusherException;

class SettingObserve
{
    /**
     * Handle the Setting "created" event.
     */
    public function created(Setting $setting): void
    {
        //
    }

    /**
     * Handle the Setting "updated" event.
     */
    public function updated(Setting $setting): void
    {
        try {
            event(new ChangeSettingEvent());
        } catch (PusherException $e) {

        } catch (\Exception | \Error $e) {

        }
    }

    /**
     * Handle the Setting "deleted" event.
     */
    public function deleted(Setting $setting): void
    {
        //
    }

    /**
     * Handle the Setting "restored" event.
     */
    public function restored(Setting $setting): void
    {
        //
    }

    /**
     * Handle the Setting "force deleted" event.
     */
    public function forceDeleted(Setting $setting): void
    {
        //
    }
}
