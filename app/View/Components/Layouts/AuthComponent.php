<?php

namespace App\View\Components\Layouts;

use App\Models\City;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AuthComponent extends Component
{
    public  $cities=[];
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
       $this->cities=City::where(['is_active' => 1,'is_main' => 1])->with('city')->get()->map(function ($item) {
           return [
               'id' => $item->id,
               'name' => $item->name,
               'city'=>[
                   'id' => $item->city->id,
                   'name' => $item->city->name,
               ]
           ];
       });
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layouts.auth-component',[
            'cities' => $this->cities,
        ]);
    }
}
