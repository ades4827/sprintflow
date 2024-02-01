# Crud system

Make Crud faster

## Usage

Add Model in sprintflow config:
```
'crud_entity' => [
    \App\Models\Admin::class => \App\Http\Controllers\Admin\AdminController::class,
    \App\Models\User::class => \App\Http\Controllers\Admin\UserController::class,
    \App\Models\Model::class => \App\Http\Controllers\Admin\ModelController::class, <--
],
```

This new value is needed in "RouteServiceProvider" on "boot" to register a model binder for a wildcard like this:
```
foreach (config('sprintflow.crud_entity') as $model => $controller) {
    Route::model($model::classSlug(), $model);
    Route::bind('deleted_'.$model::classSlug(), function ($id) use ($model) {
        return $model::withTrashed()->where('id', $id)->firstOrFail();
    });
}
```

and in routes like this:
```
foreach (config('sprintflow.crud_entity') as $model => $controller) {
    Route::crud($model, $controller, ['name_prefix' => 'admin.']);
}
```
NB: put this code under auth middleware

In crud system all controller are similar. For this you can implement the CrudController like this:
```
<?php

namespace App\Http\Controllers\Admin;

use Ades4827\Sprintflow\Controllers\CrudController;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BaseModelController extends CrudController
{
    public function __construct()
    {
        $this->model = BaseModel::class;
        parent::__construct();
    }

    public static function collection(Request $request): Builder
    {
        $entities = BaseModel::query();
        if ($request->has('filter') && $request->get('filter') != '') {
            $keywords = implode('%', explode(' ', strtolower($request->get('filter'))));
            $entities->where(function ($query) use ($keywords) {
                $query->where('name', 'LIKE', "%{$keywords}%");
            });
        }

        if (auth('admin')->user() && auth('admin')->user()->can('model_permission.restore')) {
            $entities = $entities->withTrashed();
        }

        return $entities->select('model_table.*');
    }

    public function datatable(Request $request)
    {
        $entities = self::collection($request)->newQuery();

        return Datatables::make($entities)
            ->addColumn('action', function ($model) {
                return view('sprintflow::datatable.actions.simple-crud-modal-edit', ['model' => $model, 'modal_title' => 'Modifica Model']);
            })
            ->make(true);
    }
}

```
Feel free to override any methods if necessary like in UserController.php


## Utility

The "Route::crud" (SprintflowServiceProvider.php:boot) is a route macro to summarize the following code:
```
Route::group(['prefix' => 'users'], function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/datatable', 'datatable')->name('admin.users.datatable');
        Route::get('/index', 'index')->name('admin.users.index');
        Route::get('/create/{role?}', 'create')->name('admin.users.create');
        Route::get('/{user}/edit', 'edit')->name('admin.users.edit');
        Route::get('/{user}/restore', 'restore')->withTrashed()->name('admin.users.restore');
        Route::get('/{user}/delete', 'delete')->name('admin.users.delete');
        Route::post('/{user}/status', 'changeStatus')->name('admin.users.changeStatus');
    });
});
```

