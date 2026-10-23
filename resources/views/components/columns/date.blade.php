@props([
    'value'
])
<div>
    {{ \Carbon\Carbon::make($value)->format('d/m/Y H:i:s') }}
</div>
