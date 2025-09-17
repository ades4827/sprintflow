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
    }
    protected static function booted()
    {
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

    public function getClassPlural(): string
    {
        if(!empty($this->class_plural)) {
            return $this->class_plural;
        }
        return Str::pluralStudly(class_basename($this));
    }

    public function getClassSlug($plural = false): string
    {
        $class_basename = class_basename($this);
        if ($plural) {
            $class_basename = $this->getClassPlural();
        }

        return Str::snake($class_basename);
    }

    public function getPermissionPrefix(): string
    {
        return $this->getClassSlug(true);
    }

    /**
     * Static method to get class slug
     * $entity::classSlug()
     */
    public static function classSlug($plural = false): string
    {
        $class_basename = class_basename(static::class);
        if ($plural) {
            $class_basename = Str::pluralStudly($class_basename);
        }
        return Str::snake($class_basename);
    }

    /**
     * DEPRECATED
     *
     * Static method to get table name
     * $entity::getTableName()
     */
    public static function getTableName(): string
    {
        return with(new static)->getTable();
    }
}