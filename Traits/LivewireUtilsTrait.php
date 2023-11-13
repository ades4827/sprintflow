<?php

namespace App\Traits;

use Exception;

trait LivewireUtilsTrait
{
    public function confirm($callback, ...$argv)
    {
        $component_id = $this->id;
        $this->emit('confirm', compact('component_id', 'callback', 'argv'));
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

            $this->dispatchBrowserEvent('livewire-alert', ['type' => $type,  'title' => '',  'message' => $message]);

            if (! in_array('notCloseModal', $options)) {
                $this->emit('closeModal');
            }
            if (isset($options['calendar_to_refresh'])) {
                $this->dispatchBrowserEvent('calendar_to_refresh', ['room_id' => $options['calendar_to_refresh']]);
            }
            if (isset($options['table_to_refresh'])) {
                $this->dispatchBrowserEvent('eventRefresh-datatable', ['table_selector' => $options['table_to_refresh']]);
            } else {
                dd('inserisci il valore table_to_refresh');
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

    /**
     * Returns a file size limit in bytes based on the PHP upload_max_filesize and post_max_size
     *
     * @return int
     */
    protected function file_upload_max_size()
    {
        static $max_size = -1;

        if ($max_size < 0) {
            // Start with post_max_size.
            $post_max_size = $this->parse_size(ini_get('post_max_size'));
            if ($post_max_size > 0) {
                $max_size = $post_max_size;
            }

            // If upload_max_size is less, then reduce. Except if upload_max_size is
            // zero, which indicates no limit.
            $upload_max = $this->parse_size(ini_get('upload_max_filesize'));
            if ($upload_max > 0 && $upload_max < $max_size) {
                $max_size = $upload_max;
            }
        }

        return $max_size;
    }

    /**
     * Parse file size
     *
     * @param $size
     * @return float
     */
    private function parse_size($size)
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }

        return round($size);
    }
}
