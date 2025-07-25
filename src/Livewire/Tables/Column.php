<?php

namespace Ades4827\Sprintflow\Livewire\Tables;

use Closure;
use Illuminate\Database\Eloquent\Model;

class Column
{
    public bool $orderable = false;

    public string $key;
    public string $label;
    public ?string $class = '';

    public ?string $sortBy = null;

    public string $component;
    public ?string $livewire_component;

    public ?Closure $formatter = null;

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
        $this->class .= ' '.$class;
        if (! $append) {
            $this->class = $class;
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

    public function livewireComponent(string $component): static
    {
        $this->livewire_component = $component;

        return $this;
    }

    public function sortKey(): string
    {
        return $this->sortBy;
    }

    public function value(Model $model): string
    {
        if ($this->formatter) {
            return $this->formatter->__invoke($model);
        }

        return data_get($model, $this->key);
    }
}
