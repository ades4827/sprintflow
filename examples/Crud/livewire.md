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
    // always with trashed
    //Route::model($model::classSlug(), $model);
   
    Route::bind($model::classSlug(), function ($id) use ($model) {
        return $model::findOrFail($id);
    });
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

In crud system all controller are similar. For this you can implement the CrudController or CrudEntityController like this:
```
<?php

namespace App\Http\Controllers\Admin;

use Ades4827\Sprintflow\Controllers\CrudLivewireEntityController;
use App\Models\BaseModel;

class BaseModelController extends CrudLivewireEntityController
{
    public function __construct()
    {
        $this->model = BaseModel::class;
        parent::__construct();
    }
}

```
Feel free to override any methods if necessary like in UserController.php

To list all models in the system use Livewire Table component like User section


## Utility

The "Route::crud" (SprintflowServiceProvider.php:boot) is a route macro to summarize the following code:
```
Route::group(['prefix' => 'users'], function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/index', 'index')->name('admin.users.index');
        Route::get('/create/{role?}', 'create')->name('admin.users.create');
        Route::get('/{user}/edit', 'edit')->name('admin.users.edit');
        Route::get('/{user}/restore', 'restore')->withTrashed()->name('admin.users.restore');
        Route::get('/{user}/delete', 'delete')->name('admin.users.delete');
    });
});
```

