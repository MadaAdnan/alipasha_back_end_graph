<?php

namespace App\View\Components\Layouts;

use App\Models\Setting;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NavBarComponent extends Component
{
    public $setting;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->setting=Setting::first();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layouts.nav-bar-component',[
            'setting'=>$this->setting
        ]);
    }
}
