<?php

namespace Ades4827\Sprintflow\Traits\Eloquent;

use Illuminate\Support\Carbon;

trait HasRangeScope
{
    /**
     * Scope a query for date range
     *
     * use: Model::whereRange($start, $end)->get();
     */
    public function scopeWhereRange($query, $start, $end, $inclusive = true)
    {
        $start = $start instanceof Carbon ? $start : Carbon::parse($start);
        $end = $end instanceof Carbon ? $end : Carbon::parse($end);

        if(!$inclusive) {
            $start = $start->clone()->addMinute(1);
            $end = $end->clone()->subMinute(1);
        }

        return $query->where(function ($query) use ($start, $end) {
            $query->whereBetween('from', [$start, $end])
                ->orWhereBetween('to', [$start, $end])
                ->orWhere(function ($query) use ($start, $end) {
                    $query->where('from', '<=', $start)
                        ->where('to', '>=', $end);
                });
        });
    }
}
