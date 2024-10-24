@isset( $entity )
    @isset( $field )

        @if( isset($entity->{$field}) && $entity->{$field} )
            {{ $entity->{$field} }}
        @else
            -
        @endif

    @else

        @if( isset($entity->name) && $entity->name )
            {{ $entity->name }}
        @else
            -
        @endif

    @endisset
@else
    -
@endisset