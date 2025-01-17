@if($amount != 0)
    <x-sf::money class="whitespace-nowrap">
        {{ $amount }}
    </x-sf::money>
@else
    -
@endif