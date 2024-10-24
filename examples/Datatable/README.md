# Datatable

Extend datatable with custom columns

## Installation

Add Model in datatables config:
```
'engines' => [
    'eloquent' => Ades4827\Sprintflow\DataTables\EloquentDataTable::class,
],
```

## Usage
### Crud
In datatable function you can add a crud col:
```
return Datatables::make($entities)
            ->addCrud()
            ->make();
```

### Name formatted
Add a name formatted named: name_formatted.
```
return Datatables::make($entities)
            ->addFormattedName()
            ->make();
```

Optionally you can use a relation in dot notation to show a name for this relation

```
return Datatables::make($entities)
            ->addFormattedName('post.creator')
            ->make();
```

### Field formatted
Add a custom field named: "field_name"_formatted
```
return Datatables::make($entities)
            ->addFormattedField('email')
            ->make();
```

Optionally you can use a relation in dot notation to show a custom field formatted

This column return a "-" when the model not exist
```
return Datatables::make($entities)
            ->addFormattedField('email', 'post.creator')
            ->make();
```
in frontend this field is named: post_creator_email_formatted