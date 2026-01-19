<?php

namespace App\View\Components\Components;

use App\Models\Community;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ChatComponent extends Component
{
    /**
     * @var null
     */
    public $community;

    /**
     * Create a new component instance.
     */
    public function __construct($community=null)
    {
       if($community==null){
           $this->community =   Community::whereNot('type', 'live')->whereHas('messages')->whereHas('allUsers', function ($query) {
               $query->where('users.id', auth()->id());  // جلب المجتمعات التي يشارك فيها المستخدم الحالي
           })
               /* ->where('last_update','>',now()->subDays(10))*/
               ->latest('last_update')
               ->first();
       }else{
           $this->community = $community;
       }

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.components.chat-component');
    }
}
