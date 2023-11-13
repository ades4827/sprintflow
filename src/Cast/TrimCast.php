<?php

namespace Ades4827\Sprintflow\Cast;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class TrimCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return $value;
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return trim($value);
    }
}
