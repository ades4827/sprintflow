<?php

namespace Ades4827\Sprintflow\Traits\Eloquent;

use Illuminate\Support\Carbon;

trait HasRangeScope
{
    /**
     * Scope a query for date range
     *
     * use: Model::whereRange(start: $start, end: $end, inclusive: false, start_field: 'from_date', end_field:'to_date')->get();
     */
    public function scopeWhereRange($query, $start, $end, $inclusive = true, $start_field = 'from', $end_field = 'to')
    {
        $start = $start instanceof Carbon ? $start : Carbon::parse($start);
        $end = $end instanceof Carbon ? $end : Carbon::parse($end);

        if(!$inclusive) {
            $start = $start->clone()->addMinute(1);
            $end = $end->clone()->subMinute(1);
        }

        return $query->where(function ($query) use ($start, $end, $start_field, $end_field) {
            $query->whereBetween($start_field, [$start, $end])
                ->orWhereBetween($end_field, [$start, $end])
                ->orWhere(function ($query) use ($start, $end, $start_field, $end_field) {
                    $query->where($start_field, '<=', $start)
                        ->where($end_field, '>=', $end);
                });
        });
    }
}
