@if($amount != 0)
    <x-sf::money class="whitespace-nowrap" :in="$currency">
        {{ $amount }}
    </x-sf::money>
@else
    -
@endif