<?php

namespace Ades4827\Sprintflow\Views\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Tabs extends Component
{
    public function __construct(public ?string $active)
    { }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('sprintflow::components.tabs.tabs');
    }
}
