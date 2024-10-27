<?php

namespace Ades4827\Sprintflow\Views\Components;

use Brick\Math\RoundingMode;
use Brick\Money\Context\AutoContext;
use Brick\Money\Context\CustomContext;
use Closure;
use Illuminate\View\Component;
use RuntimeException;
use Brick\Money\Money as BrickMoney;

class Money extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $number = null,
        public string $in = 'EUR',
        public ?string $locale = null,
        public ?int $precision = null,
        public ?string $replaceZero = null,
    ) {
        if(is_null($locale)) {
            $this->locale = app()->getLocale();
        }
        $this->autoScale = true;
        if(!is_null($precision)) {
            $this->autoScale = false;
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
              return '<div {{ $attributes }}>{{ $replaceZero }}</div>';
            }

            // Set number
            $this->number = $this->number ?? $data['slot']->__toString();

            // Format
            if($this->autoScale) {
                $money = BrickMoney::of($this->number, $this->in, new AutoContext())->formatTo($this->locale);
            } else {
                $money = BrickMoney::of($this->number, $this->in, new CustomContext(scale: $this->precision), roundingMode: RoundingMode::UP)->formatTo($this->locale);
            }

            return '<div {{ $attributes }}>'.$money.'</div>';
        };
    }
}
