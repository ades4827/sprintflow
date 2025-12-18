<?php

namespace Ades4827\Sprintflow\Views\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class AccordionItem extends Component
{
    public function __construct(public string $name)
    { }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('sprintflow::components.accordion.accordion-item');
    }
}
