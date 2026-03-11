# Permission management

Adds functionality for working with permissions of [Laravel-permission](https://spatie.be/docs/laravel-permission)

## Usage

First, run this command in the terminal: 

```
php artisan vendor:publish --provider="Ades4827\Sprintflow\SprintflowServiceProvider" --tag=config
```

Override the permissions and roles arrays as you need

To update the data on your database, run the artisan command: 'permission:refresh'

```
php artisan permission:refresh
```

You can run the command first in your seeds directly from php:

```
use Illuminate\Support\Facades\Artisan;

Artisan::call('permission:refresh');
```

For more flexibility, two events are launched: 'RefreshPermissionsUpdating' and 'RefreshPermissionsUpdated' which can be hooked into via a listener

Alternatively, you can overwrite the config in the ServiceProvider register so you can modify it as needed.

```
namespace App\Providers;

class AppServiceProvider extends ServiceProvider
{
    ...
    public function register()
    {
        if(config('app.name') === "Example") {
            $permissions_seeder = config('sprintflow.permissions_seeder');
    
            unset($permissions_seeder['web']['orders']);
    
            config(['sprintflow.permissions_seeder' => $permissions_seeder]);
        }
    }
    ...
}
```