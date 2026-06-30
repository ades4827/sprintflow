<?php

namespace Ades4827\Sprintflow\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use RuntimeException;

abstract class CrudLivewireEntityController extends Controller
{
    public string $model;

    public string $model_slug;

    public string $section_slug;

    public function __construct()
    {
        if (! isset($this->model)) {
            throw new RuntimeException('Missing model for this CRUD');
        }
        $model = new $this->model;
        // define names
        $this->model_slug = $model->getClassSlug();
        $this->section_slug = $model->getClassSlug(true);

        $this->middleware(['role_or_permission:'.$this->section_slug.'.view']);
        $this->middleware('permission:'.$this->section_slug.'.create')->only(['create']);
        $this->middleware('permission:'.$this->section_slug.'.update')->only(['edit', 'changeStatus']);
        $this->middleware('permission:'.$this->section_slug.'.restore')->only(['restore']);
        $this->middleware('permission:'.$this->section_slug.'.delete')->only(['destroy']);

        if (config('sprintflow.crud.verify_gates', false) && Gate::getPolicyFor($this->model)) {
            $this->authorizeResource($this->model, $this->model_slug);
        }
    }

    public function index(Request $request): View
    {
        return view('admin.'.$this->section_slug.'.index');
    }

    public function create(Request $request): View
    {
        return view('admin.'.$this->section_slug.'.form', [$this->model_slug => null, 'method' => __FUNCTION__]);
    }

    public function restore(Request $request, Model $entity): RedirectResponse
    {
        if (config('sprintflow.crud.verify_gates', false) && Gate::getPolicyFor($this->model)) {
            Gate::authorize('restore', $entity);
        }

        DB::beginTransaction();
        try {
            $entity->restore();
            DB::commit();

            return redirect()->route('admin.'.$this->section_slug.'.index')->with('status', __('sprintflow::crud.states.restore.confirm'));
        } catch (RuntimeException $e) {
            report($e);
            DB::rollBack();

            return redirect()->route('admin.'.$this->section_slug.'.index')->with('error', $e->getMessage());
        } catch (Exception $e) {
            report($e);
            DB::rollBack();
        }

        return redirect()->route('admin.'.$this->section_slug.'.index')->with('error', __('sprintflow::crud.states.restore.error'));
    }

    public function show(Request $request, Model $entity): View
    {
        return view('admin.'.$this->section_slug.'.form', [$this->model_slug => $entity, 'method' => __FUNCTION__]);
    }

    public function edit(Request $request, Model $entity): View
    {
        return view('admin.'.$this->section_slug.'.form', [$this->model_slug => $entity, 'method' => __FUNCTION__]);
    }

    public function destroy(Request $request, Model $entity): RedirectResponse
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

    /**
     * DEPRECATED
     *
     * use destroy
     */
    public function delete(Request $request, Model $entity): RedirectResponse
    {
        return $this->destroy($request, $entity);
    }
}
