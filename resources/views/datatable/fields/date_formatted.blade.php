@isset( $field )
    {{ Carbon\Carbon::parse($field)->format($format) }}
@else
    -
@endisset