<?php

namespace Ades4827\Sprintflow\Traits\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

trait HasAfterDateScope
{
    /**
     * Scope a query to filter a date after created_at, updated_at (and deleted_at if available)
     */
    public function scopeAfterDate(Builder $query, $date): Builder
    {
        $date = $date instanceof Carbon ? $date : Carbon::parse($date);

        $query->where(function ($q) use ($date) {
            $q->whereDate('created_at', '>=', $date)
                ->orWhereDate('updated_at', '>=', $date);

            // Controlla se il model usa SoftDeletes
            if (in_array(SoftDeletes::class, class_uses_recursive($this))) {
                $q->orWhereDate('deleted_at', '>=', $date);
            }
        });

        return $query;
    }
}
