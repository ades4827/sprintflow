<?php

namespace Ades4827\Sprintflow\Traits\Eloquent;

trait HasAdditionalScopes
{
    /**
     * Scope a query to filter only element enabled
     *
     * use: Model::enabled()->get();
     */
    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }
}
