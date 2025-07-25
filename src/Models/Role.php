<?php

namespace Ades4827\Sprintflow\Models;

use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    protected $hidden = [
        'pivot',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function scopeGuard(Builder $query, string $guard_name)
    {
        return $query;
    }

    public function scopeNotAdmin($query)
    {
        return $query->where('name', '!=', 'admin');
    }
}
