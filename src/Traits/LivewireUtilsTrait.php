<?php

namespace Ades4827\Sprintflow\Traits;

use Exception;

trait LivewireUtilsTrait
{
    public function confirm($callback, ...$argv)
    {
        $this->dispatch('confirm', component_id: $this->getId(), callback: $callback, argv: $argv);
    }

    protected function checkPermission($permission)
    {
        if (! auth()->user() || ! auth()->user()->can($permission)) {
            throw new Exception('User without '.$permission.' permission');
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
