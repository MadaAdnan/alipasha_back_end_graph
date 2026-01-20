<?php

namespace App\View\Components\Components;

use App\Models\Cart;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CartContainerComponent extends Component
{
   public $items=[];
    /**
     * Create a new component instance.
     */
    public function __construct($seller=null)
    {
       if($seller!=null){
           $this->items=Cart::where([
               'user_id' => auth()->id(),
               'seller_id' => $seller->id,
           ])->get();
       }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.components.cart-container-component');
    }
}
