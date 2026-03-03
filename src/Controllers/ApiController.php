<?php

namespace Ades4827\Sprintflow\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Exception;

class ApiController extends Controller
{
    /**
     * Example:
     */
    /*
    public function example_from_array(Request $request)
    {
        $all_entries = [
            0 => 'Version 1',
            1 => 'Version 2',
        ];

        return $this->getFromArray($request, $all_entries);
    }
     */
    protected function getFromArray(Request $request, array $all_entries): array
    {
        $lists = collect();
        foreach ($all_entries as $entry_id => $entry) {
            if (
                ! $request->exists('selected') ||
                ($request->exists('selected') && in_array($entry_id, $request->input('selected')))
            ) {
                $lists->push([
                    'id' => $entry_id,
                    'name' => $entry,
                ]);
            }
        }

        if ($request->input('search')) {
            $keywords = explode(' ', strtolower(trim($request->input('search'))));
            foreach ($keywords as $keyword) {
                $lists = $lists->filter(function ($item) use ($keyword) {
                    return stristr($item['name'], $keyword) !== false;
                });
            }
        }

        return $lists->values()->all();
    }

    protected function getFromCollection(Request $request, array|Collection $all_entries, $search_disabled = false)
    {
        if(is_array($all_entries)) {
            $all_entries = collect($all_entries);
        }

        $lists = collect();
        foreach ($all_entries as $entry) {
            if (
                ! $request->exists('selected') ||
                ($request->exists('selected') && in_array($entry['id'], $request->input('selected')))
            ) {
                $lists->push($entry);
            }
        }

        if (! $search_disabled && $request->input('search')) {
            $keywords = explode(' ', strtolower(trim($request->input('search'))));
            foreach ($keywords as $keyword) {
                $lists = $lists->filter(function ($item) use ($keyword) {
                    return stristr($item['name'], $keyword) !== false;
                });
            }
        }

        return $lists->values()->all();
    }

    // enum from archtechx/enums
    protected function getFromEnums(Request $request, string $enum, ?string $metadata_label = null)
    {
        $all_entries = collect($enum::cases());
        if($metadata_label) {
            $all_entries = $all_entries->mapWithKeys(function ($case) use ($metadata_label) {
                return [$case->value => $case->{$metadata_label}()];
            });
        } else {
            $all_entries = $all_entries->mapWithKeys(function ($case) {
                return [$case->value => $case->name];
            });
        }

        return $this->getFromArray($request, $all_entries->toArray());
    }

    protected function simple(Request $request, $model, $name_field = 'name', $options = [])
    {
        // https://github.com/wireui/docs/blob/main/app/Http/Controllers/Api/Users/Index.php

        $max_result_limit = 100;
        if (isset($options['max_result_limit'])) {
            $max_result_limit = $options['max_result_limit'];
        }

        // set field id name
        $field_id_name = 'id';
        if (isset($options['field_id_name'])) {
            $field_id_name = $options['field_id_name'];
        }

        $query = App::make($model)::query();

        // field to select
        if (isset($options['select'])) {
            if (is_array($options['select'])) {
                $options['select'] = implode(',', $options['select']);
            }
            $query->selectRaw($options['select']);
        } else {
            $query->select([$field_id_name, $name_field]);
        }

        // exclude ids
        if ($request->has('exclude') && is_array($request->get('exclude'))) {
            $query->whereNotIn($field_id_name, $request->get('exclude'));
        }

        // order
        if (! isset($options['disable_order_by'])) {
            if (isset($options['order_by'])) {
                $query->orderBy($options['order_by']);
            } elseif (isset($options['order_by_desc'])) {
                $query->orderBy($options['order_by_desc'], 'desc');
            } else {
                $query->orderBy($name_field);
            }
        }

        // search string --------------------------------------
        $query = $query->when(
            $request->has('search') && $request->get('search'),
            fn (Builder $query) => $query->where(function ($query) use ($request, $name_field, $options) {
                // split every word for better search
                $keywords = explode(' ', strtolower($request->input('search')));
                if (isset($options['case_sensitive'])) {
                    $keywords = explode(' ', $request->input('search'));
                }

                // search single token (slow version on many token)
                if (isset($options['search_method']) && $options['search_method'] === 'slow') {
                    foreach ($keywords as $keyword) {
                        $query->where($name_field, 'LIKE', "%{$keyword}%");
                    }

                    if (isset($options['additional_search_fields']) && is_array($options['additional_search_fields']) && count($keywords) == 1) {
                        foreach ($options['additional_search_fields'] as $search_field) {
                            if (isset($options['additional_search_method']) && $options['additional_search_method'] == 'like') {
                                foreach ($keywords as $keyword) {
                                    $query->orWhere($search_field, 'LIKE', "%{$keyword}%");
                                }
                            } else {
                                $query->orWhere($search_field, $keywords[0]);
                            }
                        }
                    }
                }
                // search string ordered (fast version)
                else {
                    $imploded_keywords = implode('%', $keywords);
                    $query->where($name_field, 'LIKE', "%{$imploded_keywords}%");

                    if (isset($options['additional_search_fields']) && is_array($options['additional_search_fields']) && count($keywords) == 1) {
                        foreach ($options['additional_search_fields'] as $search_field) {
                            if (isset($options['additional_search_method']) && $options['additional_search_method'] == 'like') {
                                $query->orWhere($search_field, 'LIKE', "%{$imploded_keywords}%");
                            } else {
                                $query->orWhere($search_field, $keywords[0]);
                            }
                        }
                    }
                }
            }),
            fn (Builder $query) => $query->limit($max_result_limit)
        );

        // parse selected to accept json_encoded
        if($request->exists('selected') && !is_array($request->input('selected'))) {
            $decoded = json_decode($request->input('selected', '[]'), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $request->merge([
                    'selected' => $decoded,
                ]);
            }
        }

        // check if model have softdelete
        $withTrashed = in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($model), true);

        // limit to selected value
        $query = $query->when(
            $request->exists('selected'),
            fn (Builder $query) => $query->whereIn($field_id_name, $request->input('selected', []))
                ->when($withTrashed, fn (Builder $query) => $query->withTrashed()),
            fn (Builder $query) => $query->limit($max_result_limit)
        );

        return $query;
    }

    protected function translated(Request $request, $model, $name_field = 'name', $options = [])
    {
        // https://github.com/wireui/docs/blob/main/app/Http/Controllers/Api/Users/Index.php

        $language = app()->getLocale();

        $max_result_limit = 100;
        if (isset($options['max_result_limit'])) {
            $max_result_limit = $options['max_result_limit'];
        }

        // set field id name
        $field_id_name = 'id';
        if (isset($options['field_id_name'])) {
            $field_id_name = $options['field_id_name'];
        }

        $query = App::make($model)::query();

        // field to select
        if (isset($options['select'])) {
            $query->selectRaw($options['select']);
        } else {
            $query->select([$field_id_name, DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".$name_field.", '$.\"$language\"')) as ".$name_field."_translated")]);
        }

        // exclude ids
        if ($request->has('exclude') && is_array($request->get('exclude'))) {
            $query->whereNotIn($field_id_name, $request->get('exclude'));
        }

        // order
        if (! isset($options['disable_order_by'])) {
            if (isset($options['order_by'])) {
                $query->orderBy($options['order_by']);
            } elseif (isset($options['order_by_desc'])) {
                $query->orderBy($options['order_by_desc'], 'desc');
            } else {
                $query->orderBy($name_field);
            }
        }

        // search string --------------------------------------
        $query = $query->when(
            $request->has('search') && $request->get('search'),
            fn (Builder $query) => $query->where(function ($query) use ($request, $name_field, $options, $language) {
                // split every word for better search
                $keywords = explode(' ', strtolower($request->input('search')));
                if (isset($options['case_sensitive'])) {
                    $keywords = explode(' ', $request->input('search'));
                }

                // search single token (slow version on many token)
                if (isset($options['search_method']) && $options['search_method'] === 'slow') {
                    foreach ($keywords as $keyword) {
                        $query->whereJsonContainsLocale($name_field, $language, "%{$keyword}%", 'LIKE');
                    }

                    if (isset($options['additional_search_fields']) && is_array($options['additional_search_fields']) && count($keywords) == 1) {
                        foreach ($options['additional_search_fields'] as $search_field) {
                            if (isset($options['additional_search_method']) && $options['additional_search_method'] == 'like') {
                                foreach ($keywords as $keyword) {
                                    $query->orWhereJsonContainsLocale($search_field, $language, "%{$keyword}%", 'LIKE');
                                }
                            } else {
                                $query->orWhereJsonContainsLocale($search_field, $language, $keywords[0]);
                            }
                        }
                    }
                }
                // search string ordered (fast version)
                else {
                    $imploded_keywords = implode('%', $keywords);
                    $query->where($name_field, 'LIKE', "%{$imploded_keywords}%");

                    if (isset($options['additional_search_fields']) && is_array($options['additional_search_fields']) && count($keywords) == 1) {
                        foreach ($options['additional_search_fields'] as $search_field) {
                            if (isset($options['additional_search_method']) && $options['additional_search_method'] == 'like') {
                                $query->orWhere($search_field, 'LIKE', "%{$imploded_keywords}%");
                            } else {
                                $query->orWhere($search_field, $keywords[0]);
                            }
                        }
                    }
                }
            }),
            fn (Builder $query) => $query->limit($max_result_limit)
        );

        // parse selected to accept json_encoded
        if($request->exists('selected') && !is_array($request->input('selected'))) {
            $decoded = json_decode($request->input('selected', '[]'), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $request->merge([
                    'selected' => $decoded,
                ]);
            }
        }

        // check if model have softdelete
        $withTrashed = in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($model), true);

        // limit to selected value
        $query = $query->when(
            $request->exists('selected'),
            fn (Builder $query) => $query->whereIn($field_id_name, $request->input('selected', []))
                ->when($withTrashed, fn (Builder $query) => $query->withTrashed()),
            fn (Builder $query) => $query->limit($max_result_limit)
        );

        return $query;
    }

    protected function checkPermissions($permissions, $guard = '')
    {
        if (! auth()->guard($guard)->user() ) {
            throw new Exception('User not logged in', 401);
        } elseif (! auth()->guard($guard)->user()->canAny($permissions)) {
            if(is_array($permissions)) {
                throw new Exception('User without '.implode(', ', $permissions).' permissions');
            } else {
                throw new Exception('User without '.$permissions.' permission');
            }
        }
    }
}
