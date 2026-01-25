<?php

namespace App\View\Components\Form;

use App\Models\Country;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InputPhoneComponent extends Component
{
    public $countries;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->countries=Country::where('is_active',1)->get()->map(function ($item) {
            return [
                'id'=>$item->id,
                'name'=>$item->name,
                'code'=>$item->code
            ];
        });
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.input-phone-component',[
            'countries'=>$this->countries
        ]);
    }
}
