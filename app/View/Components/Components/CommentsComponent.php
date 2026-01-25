<?php

namespace App\View\Components\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CommentsComponent extends Component
{


    /**
     * Create a new component instance.
     */
    public function __construct($post=null)
    {

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.components.comments-component');
    }
}
