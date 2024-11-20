@if( isset($field) && $field )
    {{ \Carbon\Carbon::parse($field)->format($format) }}
@else
    -
@endif