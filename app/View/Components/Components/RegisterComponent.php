<?php

namespace App\View\Components\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RegisterComponent extends Component
{
     public $governorates;
    public $cities;
    /**
     * Create a new component instance.
     */
    public function __construct()
    { $this->governorates =City::whereIsMain(true)->get()->toArray();
        $this->cities = City::where('is_main',false)->whereNotNull('city_id')->get();

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.components.register-component',[
            'cities' => $this->cities,
        ]);
    }
}
