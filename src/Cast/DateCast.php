<?php

namespace Ades4827\Sprintflow\Cast;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class DateCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        if ($value) {
            return Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y');
        }

        return '';
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if (Carbon::hasFormat($value, 'd/m/Y')) {
            return Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
        }
        if (Carbon::hasFormat($value, 'Y-m-d')) {
            return $value;
        }
    }
}
