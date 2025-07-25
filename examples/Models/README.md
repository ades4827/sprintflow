# Models

- [Model utils](src/Traits/BaseModelTrait.php)
- [Default Models](src/Models)

You can override or integrate the Default Models locally using the following code in the ServiceProvider

```
$this->app->bind(\Ades4827\Sprintflow\Models\Admin::class, \App\Models\Admin::class);
$this->app->bind(\Ades4827\Sprintflow\Models\Role::class, \App\Models\Role::class);
$this->app->bind(\Ades4827\Sprintflow\Models\User::class, \App\Models\User::class);
```