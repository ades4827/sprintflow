<?php

namespace Ades4827\Sprintflow\Views\Components;

use Ades4827\Sprintflow\Helpers\NumberHelper;
use Closure;
use Illuminate\Support\Number;
use Illuminate\View\Component;
use RuntimeException;

class NumberFormatter extends Component
{
    private bool $autoScale;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $number = null,
        public ?int $precision = 2,
        public ?int $maxPrecision = null,
        public ?string $locale = null,
        public ?string $replaceZero = null,
        public ?string $uom = null,
        public string $uomPrefix = ' ',
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
            if( !is_null($this->replaceZero) &&
                (
                    ($this->number !== null && \App\Helpers\NumberHelper::isEffectivelyZero($this->number)) ||
                    ($this->number === null && NumberHelper::isEffectivelyZero($data['slot']->__toString()))
                )
            ) {
                if($this->inline) {
                    return '<span {{ $attributes }}>{{ $replaceZero }}</span>';
                }
                return '<div {{ $attributes }}>{{ $replaceZero }}</div>';
            }

            // Set number
            $this->number = $this->number ?? $data['slot']->__toString();

            // Format
            $number = Number::format($this->number, precision: $this->precision, maxPrecision: $this->maxPrecision, locale: $this->locale);

            // Append UOM
            if($this->uom) {
                $number .= $this->uomPrefix . $this->uom;
            }
            $output = $number;

            if($this->inline) {
                return '<span {{ $attributes }}>'.$output.'</span>';
            }
            return '<div {{ $attributes }}>'.$output.'</div>';
        };
    }
}
