<?php

namespace Ades4827\Sprintflow\Traits;

use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

trait LivewireUtilsTrait
{
    public function confirm($callback, ...$argv)
    {
        $this->dispatch('confirm', component_id: $this->getId(), callback: $callback, argv: $argv);
    }

    protected function checkPermission($roleOrPermission, $guard = null) {
        if($guard == null) {
            $guard = config('auth.defaults.guard');
        }

        if (Auth::guard($guard)->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $rolesOrPermissions = is_array($roleOrPermission)
            ? $roleOrPermission
            : explode('|', $roleOrPermission);

        if (! Auth::guard($guard)->user()->hasAnyRole($rolesOrPermissions) && ! Auth::guard($guard)->user()->hasAnyPermission($rolesOrPermissions)) {
            throw UnauthorizedException::forRolesOrPermissions($rolesOrPermissions);
        }
    }

    /*
     *
     * $options: ['notCloseModal', 'table_to_refresh' => '#id_table', 'route_parameters' => ['param_name' => $val] ]
     */
    public function return($message, $route_name = null, array $options = [])
    {
        // if is modal
        if ($route_name == null) {
            // type
            $type = 'success';
            if (isset($options['type'])) {
                $type = $options['type'];
            }

            $this->dispatch('livewire-alert', type: $type, title: '', message: $message);

            if (! in_array('notCloseModal', $options)) {
                $this->dispatch('closeModal');
            }
            if (isset($options['table_to_refresh'])) {
                $this->dispatch('eventRefresh-datatable', table_selector: $options['table_to_refresh']);
            } else {
                dd('Inserisci il valore table_to_refresh');
            }

            return null;
        }
        // route_parameters
        $route_parameters = [];
        if (isset($options['route_parameters'])) {
            $route_parameters = $options['route_parameters'];
        }

        // rediret to page
        if ($message != '') {
            return redirect()->route($route_name, $route_parameters)->with('status', $message);
        }

        return redirect()->route($route_name, $route_parameters);
    }
}
