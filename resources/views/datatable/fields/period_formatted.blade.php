@if( isset($from_field) && $from_field && isset($to_field) && $to_field )
    {{ \Carbon\Carbon::parse($from_field)->format($format).$separator.\Carbon\Carbon::parse($to_field)->format($format) }}
@else
    -
@endif