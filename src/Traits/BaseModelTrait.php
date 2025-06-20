<?php

namespace Ades4827\Sprintflow\Traits;

use Illuminate\Support\Str;

trait BaseModelTrait
{
    protected $class_plural = null;

    public static function getTableName(): string
    {
        return with(new static)->getTable();
    }

    public function getPermissionPrefix(): string
    {
        return $this->getClassSlug(true);
    }

    public function getClassPlural(): string
    {
        if(!empty($this->class_plural)) {
            return $this->class_plural;
        }
        return Str::pluralStudly(class_basename($this));
    }

    public function getClassSlug($plural = false): string
    {
        if ($plural) {
            return Str::snake($this->getClassPlural());
        }

        return Str::snake(class_basename($this));
    }

    public static function classSlug($plural = false): string
    {
        if ($plural) {
            return Str::snake(Str::pluralStudly(class_basename(static::class)));
        }

        return Str::snake(class_basename(static::class));
    }
}
