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

    public function addBoolean(string $model_field, string $false_fallback = null)
    {
        return $this->addColumn($model_field.'_formatted', function ($model) use ($model_field, $false_fallback) {
            return view('sprintflow::datatable.fields.generic-boolean', ['value' => $model->{$model_field}, 'false_fallback' => $false_fallback]);
        });
    }

    public function addMoney(string $model_field)
    {
        return $this->addColumn($model_field.'_formatted', function ($model) use ($model_field) {
            return view('sprintflow::datatable.fields.generic-money', ['amount' => $model->{$model_field}]);
        });
    }

    public function addDate(string $model_field, string $format = 'Y-m-d')
    {
        return $this->addColumn($model_field.'_date_formatted', function ($model) use ($model_field, $format) {
            return view('sprintflow::datatable.fields.date_formatted', ['field' => $model->{$model_field}, 'format' => $format]);
        });
    }

    public function addPeriod(string $from_field, string $to_field, string $format = 'Y-m-d', string $separator = ' / ', string $col_name = null)
    {
        if($col_name == null) {
            $col_name = $from_field.'_'.$to_field.'_period_formatted';
        }

        return $this->addColumn($col_name, function ($model) use ($from_field, $to_field, $format, $separator) {
            return view('sprintflow::datatable.fields.period_formatted', [
                'from_field' => $model->{$from_field},
                'to_field' => $model->{$to_field},
                'format' => $format,
                'separator' => $separator,
            ]);
        });
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