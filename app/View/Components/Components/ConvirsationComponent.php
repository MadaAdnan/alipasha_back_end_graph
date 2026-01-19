<?php

namespace App\View\Components\Components;

use App\Models\Community;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ConvirsationComponent extends Component
{
    public $communities=[];

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
        $this->communities =  $communities = Community::whereNot('type', 'live')->whereHas('messages')->whereHas('allUsers', function ($query) {
            $query->where('users.id', auth()->id());  // جلب المجتمعات التي يشارك فيها المستخدم الحالي
        })
            ->where('last_update','>',now()->subDays(10))
            ->latest('last_update')
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.components.convirsation-component');
    }
}
