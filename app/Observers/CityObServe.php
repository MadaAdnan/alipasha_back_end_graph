<?php

namespace App\Observers;

use App\Models\City;

class CityObServe
{
    /**
     * Handle the City "created" event.
     */
    public function created(City $city): void
    {
        if($city->is_main==true){

            $city->update(['city_id'=>$city->id]);
            $old=$city->getOriginal('is_delivery');
            if($old!=$city->is_delivery){
                $city->children()->update(['is_delivery'=>$city->is_delivery]);
            }
        }

    }

    /**
     * Handle the City "updated" event.
     */
    public function updated(City $city): void
    {
        //
    }

    /**
     * Handle the City "deleted" event.
     */
    public function deleted(City $city): void
    {
        //
    }

    /**
     * Handle the City "restored" event.
     */
    public function restored(City $city): void
    {
        //
    }

    /**
     * Handle the City "force deleted" event.
     */
    public function forceDeleted(City $city): void
    {
        //
    }
}
