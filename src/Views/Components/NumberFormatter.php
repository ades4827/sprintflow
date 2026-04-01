<?php

namespace Ades4827\Sprintflow\Views\Components;

use Closure;
use Illuminate\View\Component;
use RuntimeException;
use Illuminate\Support\Number;

class NumberFormatter extends Component
{
    private bool $autoScale;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $number = null,
        public ?int $precision = 2,
        public ?string $locale = null,
        public ?string $replaceZero = null,
        public ?bool $inline = false,
    ) {
        if(is_null($locale)) {
            $this->locale = app()->getLocale();
        }
    }

    public function render(): Closure
    {
        return function (array $data) {
            // Error for wrong slot data
            if($data['slot']->isNotEmpty() && (float) $data['slot']->__toString() != $data['slot']->__toString()) {
                throw new RuntimeException('Wrong slot value in money component: ' . $data['slot']->__toString());
            }

            // Replace zero
            if(!is_null($this->replaceZero) && ($this->number === 0 || $this->number === '0' || $data['slot']->__toString() === 0 || $data['slot']->__toString() === '0')) {
                if($this->inline) {
                    return '<span {{ $attributes }}>{{ $replaceZero }}</span>';
                }
                return '<div {{ $attributes }}>{{ $replaceZero }}</div>';
            }

            // Set number
            $this->number = $this->number ?? $data['slot']->__toString();

            // Format
            $number = Number::format($this->number, precision: $this->precision, locale: $this->locale);

            if($this->inline) {
                return '<span {{ $attributes }}>'.$number.'</span>';
            }
            return '<div {{ $attributes }}>'.$number.'</div>';
        };
    }
}
