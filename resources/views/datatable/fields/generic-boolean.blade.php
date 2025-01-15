@if( $value )
    <i class="fa-solid fa-check text-success"></i>
@else
    @if(isset($false_fallback) && $false_fallback)
        {!! $false_fallback !!}
    @else
        <i class="fa-solid fa-times text-danger"></i>
    @endif
@endif