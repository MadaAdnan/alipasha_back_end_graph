<?php

namespace App\View\Components\Web\Components\Layouts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NavbarConponent extends Component
{
    public string $title;

    /**
     * Create a new component instance.
     */
    public function __construct($title='علي باشا')
    {
        //
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.web.components.layouts.navbar-conponent');
    }
}
