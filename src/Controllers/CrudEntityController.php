<?php

namespace Ades4827\Sprintflow\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use RuntimeException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

abstract class CrudEntityController extends Controller
{
    public string $model;
    public string $model_slug;
    public string $section_slug;

    public function __construct()
    {
        if( !isset($this->model) ) {
            throw new \RuntimeException('Missing model for this CRUD');
        }
        $model = new $this->model();
        // define names
        $this->model_slug = $model->getClassSlug();
        $this->section_slug = $model->getClassSlug(true);

        $this->middleware(['role_or_permission:'.$this->section_slug.'.view']);
        $this->middleware('permission:'.$this->section_slug.'.create')->only(['create']);
        $this->middleware('permission:'.$this->section_slug.'.update')->only(['edit', 'changeStatus']);
        $this->middleware('permission:'.$this->section_slug.'.restore')->only(['restore']);
        $this->middleware('permission:'.$this->section_slug.'.delete')->only(['delete']);
    }

    /**
     * Example:
     *
    public static function collection(Request $request) {
        $entities = Model::with(['relation']);
        if ($request->has('filter') && $request->get('filter')!='') {
            $keywords = implode('%',explode(' ', strtolower($request->get('filter'))));
            $entities->where(function($query) use ($keywords) {
                $query->where( 'name', 'LIKE', "%{$keywords}%" );
            });
        }
        if(auth('admin')->user() && auth('admin')->user()->can('section_slug.restore')) {
            $entities = $entities->withTrashed();
        }
        return $entities->select('section_slug.*');
    }
    */
    abstract public static function collection(Request $request): Builder;

    /**
     * Example:
     *
     public function datatable(Request $request) {
         $entities = self::collection($request)->newQuery();
         return DataTables::make($entities)
         ->addColumn('action', function ($model) {
            return view('sprintflow::datatable.actions.simple-crud-edit', ['model' => $model]);
         })
         ->make(true);
     }
     */
    abstract public function datatable(Request $request);

    public function index(Request $request): View
    {
        return view('admin.'.$this->section_slug.'.index');
    }

    public function create(Request $request): View
    {
        return view('admin.'.$this->section_slug.'.form', [$this->model_slug => null]);
    }

    public function restore(Request $request, Model $entity): RedirectResponse
    {
        $entity->restore();
        return redirect()->route('admin.'.$this->section_slug.'.index')->with('status', __('sprintflow::crud.states.restored'));
    }

    public function edit(Request $request, Model $entity): View
    {
        return view('admin.'.$this->section_slug.'.form', [$this->model_slug => $entity]);
    }

    public function delete(Request $request, Model $entity): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $entity->delete();
            DB::commit();

            return redirect()->route('admin.'.$this->section_slug.'.index')->with('status', __('sprintflow::crud.states.delete.confirm'));
        } catch (RuntimeException $e) {
            report($e);
            DB::rollBack();
            return redirect()->route('admin.'.$this->section_slug.'.index')->with('error', $e->getMessage());
        } catch (Exception $e) {
            report($e);
            DB::rollBack();
        }

        return redirect()->route('admin.'.$this->section_slug.'.index')->with('error', __('sprintflow::crud.states.delete.error'));
    }

    public function changeStatus(Request $request, Model $entity): JsonResponse
    {
        $status = $request->input('status');

        $entity->is_enabled = 0;
        if ($status == 'true') {
            $entity->is_enabled = 1;
        }
        $entity->save();

        return response()->json([
            'status' => $status,
        ]);
    }
}
