<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait BaseModelTrait
{
    public static function getTableName(): string
    {
        return with(new static)->getTable();
    }

    public function getPermissionPrefix(): string
    {
        return $this->getClassSlug(true);
    }

    public function getClassSlug($plural = false): string
    {
        if ($plural) {
            return $this->table ?? Str::snake(Str::pluralStudly(class_basename($this)));
        }

        return Str::snake(class_basename($this));
    }
}
