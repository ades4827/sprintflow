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
### 1. Crud
In datatable function you can add a crud col. 
You can use the 3 OPTIONAL parameters: 
- name of the column you are adding
- if the change is done in modal
- set of font-awesome icon: 'fa-solid', 'fa-light'
```
return DataTables::make($entities)
            ->addCrud('col_name', true, 'fa-solid')
            ->make();
```

### 2. Name formatted
Add a name formatted named: name_formatted.
```
return DataTables::make($entities)
            ->addFormattedName()
            ->make();
```

Optionally you can use a relation in dot notation to show a name for this relation

```
return DataTables::make($entities)
            ->addFormattedName('post.creator')
            ->make();
```

### 3. Field formatted
Add a custom field named: "field_name"_formatted
```
return DataTables::make($entities)
            ->addFormattedField('email')
            ->make();
```

Optionally you can use a relation in dot notation to show a custom field formatted

This column return a "-" when the model not exist
```
return DataTables::make($entities)
            ->addFormattedField('email', 'post.creator')
            ->make();
```
in frontend this field is named: post_creator_email_formatted

### 4. Date formatted
Add a date field formatted: created_at_date_formatted. This column return a "-" when field is empty
```
return DataTables::make($entities)
            ->addDate('created_at', 'd-m-Y')
            ->make();
```

### 5. Period formatted
Add a period field formatted: created_at_deleted_at_period_formatted. This column return a "-" when field is empty
```
return DataTables::make($entities)
            ->addPeriod('created_at', 'deleted_at', 'd-m-Y', '/', 'field_name')
            ->make();
```

### 6. Boolean formatted
Add a boolean field formatted: is_default_formatted. This column return a "-" when field is false
```
return DataTables::make($entities)
            ->addBoolean('is_default', 'false_fallback')
            ->make();
```

### 6. Money formatted
Add a money field formatted: amount_formatted. This column return a "-" when field is 0
```
return DataTables::make($entities)
            ->addMoney('amount', 'currency')
            ->make();
```