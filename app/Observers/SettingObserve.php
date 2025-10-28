<?php

namespace App\Observers;

use App\Events\ChangeSettingEvent;
use App\Models\Setting;
use Mockery\Exception;
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


    public function updating(Setting $setting)
    {

        //dollar.idlib.usd.bay
        $setting->dollar_value = $setting->dollar['idlib']['usd']['bay'] ?? $setting->dollar_value;
        $setting->dollar_syr = $setting->dollar['idlib']['syr']['bay'] ?? $setting->dollar_syr;
        $social = $setting->getOriginal('social');
        if ($setting->social['name'] == '') {
            $setting->social['name'] = $social['name'];
        }
        if ($setting->social['email'] == '') {
            $setting->social['email'] = $social['email'];
        }
    }

    /**
     * Handle the Setting "updated" event.
     */
    public function updated(Setting $setting): void
    {
        try {
            broadcast(new ChangeSettingEvent());
        } catch (\Exception|\Error $e) {
            info('Error Event Setting' . $e->getMessage());
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
