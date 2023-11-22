<?php

namespace Ades4827\Sprintflow\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class YesBooleanCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        if ($value === 'yes' || $value === 1 || $value === true) {
            return 'yes';
        }

        return '';
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if ($value === 'yes' || $value === 1 || $value === true) {
            return true;
        }

        return false;
    }
}
