<?php

namespace Ades4827\Sprintflow\Traits;

use Illuminate\Support\Str;

trait BaseModelTrait
{
    /*
    protected $hidden = [
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    */

    /*
    protected $casts = [
        'is_valid' => 'boolean',
        'created_at' => 'datetime',
        'datas' => 'object',
    ];
    */

    /*
    composer require spatie/laravel-translatable
    use Spatie\Translatable\HasTranslations;
    public array $translatable = [
        'name'
    ];
    */

    /*
    public static function boot()
    {
        static::addGlobalScope('valid', function (Builder $builder) {
            $builder->where('is_valid', true);
        });
        parent::boot();
        static::creating(static function ($model) {
            if (empty($model->slug)) {
                $slug = \Illuminate\Support\Str::slug($model->name);
                //$slug = Str::slug($model->translate('name', 'en'));
                $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
                $model->slug = $count ? "{$slug}-{$count}" : $slug;
            }
        });
    }
    */

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
