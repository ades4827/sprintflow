<?php

namespace Ades4827\Sprintflow\DataTables;

class EloquentDataTable extends \Yajra\DataTables\EloquentDataTable
{
    public function addCrud(string $name = 'action', bool $edit_in_modal = false)
    {
        return $this->addColumn($name, function ($model) use ($edit_in_modal) {
            if($edit_in_modal) {
                return view('sprintflow::datatable.actions.simple-crud-modal-edit', ['model' => $model]);
            }
            return view('sprintflow::datatable.actions.simple-crud-edit', ['model' => $model]);
        });
    }

    public function addFormattedName()
    {
        return $this->addFormattedField();
    }

    public function addFormattedField(string $model_field = 'name')
    {
        return $this->addColumn($model_field.'_formatted', function ($model) use ($model_field) {
            return view('sprintflow::datatable.fields.simple-entity-field', ['entity' => $model, 'field' => $model_field]);
        });
    }
}
