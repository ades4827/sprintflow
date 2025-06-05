<?php

namespace Ades4827\Sprintflow\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class TrimCast implements CastsAttributes
{
    protected bool $keepEmptyString;

    public function __construct(string $option = null)
    {
        // Se viene passato "empty", mantieni le stringhe vuote
        $this->keepEmptyString = $option === 'empty';
    }

    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (is_bool($value) || is_numeric($value) || is_null($value)) {
            return $value;
        }

        $trimmed = trim((string) $value);

        if ($this->keepEmptyString) {
            return $trimmed;
        }

        return $trimmed !== '' ? $trimmed : null;
    }
}
