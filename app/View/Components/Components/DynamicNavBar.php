<?php

namespace App\View\Components\Components;

use App\Models\Page;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DynamicNavBar extends Component
{
    public $navs;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->navs=Page::where('active',1)->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.components.dynamic-nav-bar');
    }
}
