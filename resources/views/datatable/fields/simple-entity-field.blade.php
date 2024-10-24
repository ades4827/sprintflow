@isset( $entity )
    @isset( $field )
        {{ $entity->{$field} }}
    @else
        {{ $entity->name }}
    @endisset
@else
    -
@endisset
