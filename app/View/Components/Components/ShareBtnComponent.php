<?php

namespace App\View\Components\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ShareBtnComponent extends Component
{
    public $url;
    public $title;
    public $description;
    public $image;
    public $showLabel;
    public $platforms;
    
    /**
     * Create a new component instance.
     */
    public function __construct(
        string $url = '', 
        string $title = '', 
        string $description = '', 
        string $image = '', 
        bool $showLabel = true,
        array $platforms = []
    ) {
        $this->url = $url ?: request()->url();
        $this->title = $title;
        $this->description = $description;
        $this->image = $image;
        $this->showLabel = $showLabel;
        $this->platforms = $platforms ?: ['facebook', 'twitter', 'whatsapp', 'telegram', 'linkedin'];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.components.share-btn-component');
    }
}
