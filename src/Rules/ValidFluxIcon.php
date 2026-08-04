<?php

namespace Ades4827\Sprintflow\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Cache;

class ValidFluxIcon implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        $exists = Cache::remember("flux_icon_exists_{$value}", now()->addHour(), function () use ($value) {
            $paths = [
                // Icone Heroicons bundled di default con Flux
                base_path("vendor/livewire/flux/stubs/resources/views/flux/icon/{$value}.blade.php"),
                // Icone custom/Lucide importate nel progetto
                resource_path("views/flux/icon/{$value}.blade.php"),
            ];

            foreach ($paths as $path) {
                if (file_exists($path)) {
                    return true;
                }
            }

            return false;
        });

        if (! $exists) {
            $fail('Il campo :attribute deve essere il nome di un\'icona Flux valida (Heroicons o Lucide).');
        }
    }
}
