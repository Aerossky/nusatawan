<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class button extends Component
{
    public $textColor;
    public $href;
    /**
     * Create a new component instance.
     */
    public function __construct($textColor = 'text-white', $href = '#')
    {
        //
        $this->textColor = $textColor;
        $this->href = $href;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.button');
    }
}
