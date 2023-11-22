# Permission management

Adds functionality for working with permissions of [Laravel-permission](https://spatie.be/docs/laravel-permission)

## Usage

First run this command in terminal: 

```
php artisan vendor:publish --provider="Ades4827\Sprintflow\SprintflowServiceProvider" --tag=config
```

Override the permissions and roles arrays as you need

To update the data on your database run the artisan command: 'permission:refresh'

```
php artisan permission:refresh
```

You can run the command first in your seeds directly from php:

```
use Illuminate\Support\Facades\Artisan;

Artisan::call('permission:refresh');
```
