<?php

namespace App\View\Components\Components;

use App\Models\Product;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SamePostComponent extends Component
{
    private $category;
    public $products = [];

    /**
     * Create a new component instance.
     */
    public function __construct($category)
    {
        //
        $this->category = $category;
        $this->products = Product::active()->upTo20()->where('sub1_id', $category)->limit(4)->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.components.same-post-component');
    }
}
