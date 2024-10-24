<?php

namespace Ades4827\Sprintflow\DataTables;

class EloquentDataTable extends \Yajra\DataTables\EloquentDataTable
{
    public function addCrud(string $name = 'action', bool $edit_in_modal = false)
    {
        return $this->addColumn($name, function ($model) use ($edit_in_modal) {
            if ($edit_in_modal) {
                return view('sprintflow::datatable.actions.simple-crud-modal-edit', ['model' => $model]);
            }
            return view('sprintflow::datatable.actions.simple-crud-edit', ['model' => $model]);
        });
    }

    public function addFormattedName(string $relation = null)
    {
        return $this->addFormattedField('name', $relation);
    }

    public function addFormattedField(string $model_field = 'name', string $relation = null)
    {
        $column_name = [];
        if ($relation) {
            $column_name = explode('.', $relation);
        }
        $column_name[] = $model_field;
        $column_name[] = 'formatted';

        return $this->addColumn(implode('_', $column_name), function ($model) use ($model_field, $relation) {
            if ($relation) {
                $parts = explode('.', $relation);
                $current = $model;
                foreach ($parts as $part) {
                    if ($current instanceof Illuminate\Database\Eloquent\Collection) {
                        $current = $current->map(function ($item) use ($part) {
                            return $item->{$part};
                        });
                    } else {
                        $current = $current->{$part};
                    }
                }
                $model = $current;
            }
            return view('sprintflow::datatable.fields.simple-entity-field', ['entity' => $model, 'field' => $model_field]);
        });
    }
}