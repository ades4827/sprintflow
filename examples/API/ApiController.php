<?php

namespace App\Http\Controllers\Api;

use Ades4827\Sprintflow\Controllers\ApiController as SprintApiController;
use App\Models\Role;
use App\Models\Page;
use Illuminate\Http\Request;

class ApiController extends SprintApiController
{
    public function pages(Request $request)
    {
        return $this->simple($request, Page::class)->get();
    }
    public function complex_roles(Request $request)
    {
        $query = $this->simple($request, Role::class, 'readable_name', [
            'field_id_name' => 'id',
            'order_by' => 'name',
            'search_method' => 'slow', // search every string part separated by space (lower query)
            'additional_search_string' => 'complete_name', // search in other field (must add this field in select)
            'select' => 'id, name, complete_name',
        ]);
        // add specific filter from request
        if($request->has('guard')) {
            $query->where('guard_name', $request->input('guard'));
        }
        if (! auth('admin')->user()->hasRole(['admin'])) {
            $query = $query->notAdmin();
        }
        return $query->get();
    }
}
