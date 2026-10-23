<?php

namespace Ades4827\Sprintflow\Livewire\Tables;

use Closure;
use Illuminate\Database\Eloquent\Model;

class Column
{
    public bool $orderable = false;

    public string $key;
    public string $label;
    public ?string $col_class = '';
    public string $component_classes = '';

    public ?string $sortBy = null;

    public string $component;
    public ?string $livewire_component;

    public ?Closure $formatter = null;
    public ?string $skin = null;
    public ?Closure $skinVariant = null;

    public function __construct(string $key, string $label, $component = 'columns.column', $livewire_component = null)
    {
        $this->key = $key;
        $this->label = $label;
        $this->component = $component;
        $this->livewire_component = $livewire_component;
    }

    public static function make(string $key, string $label): static
    {
        return new static($key, $label);
    }

    public static function checkbox(string $key = 'id')
    {
        return new static($key, '', '', 'checkbox');
    }

    public function class(string $class, bool $append = true): static
    {
        $this->col_class .= ' '.$class;
        if (! $append) {
            $this->col_class = $class;
        }

        return $this;
    }

    public function set_component_classes(string $class, bool $append = true): static {
        $this->component_classes .= ' '.$class;
        if (! $append) {
            $this->component_classes = $class;
        }

        return $this;
    }

    public function component(string $component): static
    {
        $this->component = $component;

        return $this;
    }

    public function orderable(?string $sortBy = null): static
    {
        $this->orderable = true;
        $this->sortBy = $this->key;
        if ($sortBy) {
            $this->sortBy = $sortBy;
        }

        return $this;
    }

    public function formatter(Closure $formatter): static
    {
        $this->formatter = $formatter;

        return $this;
    }

    public function skin(string $name, Closure $skinVariant): static
    {
        $this->skin = $name;
        $this->skinVariant = $skinVariant;

        return $this;
    }

    public function livewireComponent(string $component): static
    {
        $this->livewire_component = $component;

        return $this;
    }

    public function sortKey(): string
    {
        return $this->sortBy;
    }

    // getter from blade
    public function value(Model $model): ?string
    {
        if ($this->formatter) {
            return $this->formatter->__invoke($model);
        }

        return data_get($model, $this->key);
    }

    // getter from blade
    public function classes(Model $model): ?string
    {
        if ($this->skin && $this->skinVariant) {
            if($this->skin === 'badge') {
                $variant = $this->skinVariant->__invoke($model);
                if($variant === 'success') {
                    $this->component_classes = 'rounded-full px-3 bg-green-200 text-green-700';
                }
                if($variant === 'info') {
                    $this->component_classes = 'rounded-full px-3 bg-cyan-100 text-cyan-800';
                }
                if($variant === 'warning') {
                    $this->component_classes = 'rounded-full px-3 bg-orange-200 text-orange-600';
                }
                if($variant === 'danger') {
                    $this->component_classes = 'rounded-full px-3 bg-red-200 text-red-600';
                }
            }
        }

        return $this->component_classes;
    }
}
