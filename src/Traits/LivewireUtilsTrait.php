<?php

namespace Ades4827\Sprintflow\Traits;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

trait LivewireUtilsTrait
{
    use AuthorizesRequests;

    public function confirm($callback, ...$argv)
    {
        $this->dispatch('confirm', component_id: $this->getId(), callback: $callback, argv: $argv);
    }

    public function confirmCallback($callback, $options, ...$argv)
    {
        if(!is_array($options)) {
            throw new Exception('Options must be an array');
        }
        $swal_params = null;
        $title = null;
        $text = null;
        $icon = null;
        $btnconfirm = null;
        $btncancel = null;
        if (isset($options['swal_params'])) {
            $swal_params = $options['swal_params'];
        }
        if (isset($options['title'])) {
            $title = $options['title'];
        }
        if (isset($options['text'])) {
            $text = $options['text'];
        }
        if (isset($options['icon'])) {
            $icon = $options['icon'];
        }
        if (isset($options['btnconfirm'])) {
            $btnconfirm = $options['btnconfirm'];
        }
        if (isset($options['btncancel'])) {
            $btncancel = $options['btncancel'];
        }
        $this->dispatch('confirm', component_id: $this->getId(), callback: $callback, argv: $argv,
            swal_params: $swal_params, title: $title, text: $text, icon: $icon, btnconfirm: $btnconfirm, btncancel: $btncancel);
    }

    /**
     * @throws Exception
     */
    protected function checkPermission($permission, $guard = null)
    {
        /*if($guard==null) {
            $available_guards = array_keys(config('auth.guards'));
            if(in_array('admin', $available_guards)) {
                $guard = 'admin';
            }
        }*/

        $user = auth()->guard($guard)->user();
        if (!$user || !$user->can($permission)) {
            abort(403, 'User without "'.$permission.'" permission');
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

    private function model_to_options(string $model) {
        $options = [];
        foreach ($model::pluck('name', 'id') as $id => $name) {
            $options[] = [
                'id' => $id,
                'name' => $name,
            ];
        }
        return $options;
    }
}
