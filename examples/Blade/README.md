# Blade utils

Component for speed up your blade experience

## Money

You can format your number in money simply like this:
```
<x-sf::money number="120" />
<x-sf::money>120</x-sf::money>
<x-sf::money>
    {{ 123+$shippingCost }}
</x-sf::money>
```

By default use `EUR` with app locale and accept custom attributes:
```
<x-sf::money number="120" class="bg-red" />
```

There are some option:
- in: currency code 3 char
- locale: locale code 2 char for country convention like dot and coma position
- auto-scale: (implicit setted to true) when true automatically set the precision to the number like 10,00 to 10
- precision: number of decimal place (when setted auto-scale is set to false)
- replace-zero: char to replace zero value
```
<x-sf::money number="120" in="USD" /> Output: 120 USD
<x-sf::money number="120" locale="en" /> Output: €120
<x-sf::money number="120" in="USD" locale="en" /> Output: $120
<x-sf::money number="20000.0000" /> Output: 20.000 €
<x-sf::money number="20000.65254" precision="4" /> Output: 20.000,6526 €
<x-sf::money number="0" replace-zero="-" /> Output: -
```