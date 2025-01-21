<?php

namespace App\View\Components\Web\Components\Layouts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LayoutComponent extends Component
{
    public $keywords;
    public $description;
    public $title;

    /**
     * Create a new component instance.
     */
    public function __construct($keywords='متجر, علي باشا, تسويق, تسويق إلكتروني',$description='متجر علي باشا للتسويق الرقمي',$title='علي باشا')
    {
        //
        $this->keywords = $keywords;
        $this->description = $description;
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.web.components.layouts.layout-component');
    }
}
