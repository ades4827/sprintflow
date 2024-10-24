# Datatable

Extend datatable with custom columns

## Usage

Add Model in datatables config:
```
'engines' => [
    'eloquent' => Ades4827\Sprintflow\DataTables\EloquentDataTable::class,
],
```

In datatable function you can add a crud col:
```
return Datatables::make($entities)
            ->addCrud()
            ->make();
```

Or add a name formatted as: name_formatted
```
return Datatables::make($entities)
            ->addFormattedName()
            ->make();
```

Or add a field only when model is setted
```
return Datatables::make($entities)
            ->addFormattedField('email')
            ->make();
```
in frontend this field is named: email_formatted