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
- inline: boolean value to switch from div to span
```
<x-sf::money number="120" in="USD" /> Output: 120 USD
<x-sf::money number="120" locale="en" /> Output: €120
<x-sf::money number="120" in="USD" locale="en" /> Output: $120
<x-sf::money number="20000.0000" /> Output: 20.000 €
<x-sf::money number="20000.65254" precision="4" /> Output: 20.000,6526 €
<x-sf::money number="0" replace-zero="-" /> Output: -
<x-sf::money number="120" :inline="true" />
```

## Accordion
Simple accordion with alpinejs

In the accordion component you can use a opened-item-name attribute for select first open element

multi-open attribute to false switch from item to another

Every accordionItem need a custom name as required attribute

The body section for the accordionItem component is a slot or you can use a body slot to override a class
```
<x-sf::accordion multi-open="true" opened-item-name="two" class="overrided">
    <x-sf::accordionItem name="one">
        <x-slot:title class="overrided">
            <div>1</div>
            What browsers are supported?
        </x-slot>
        <x-slot:body class="overrided p-6">
            Our website is optimized for the latest versions of Chrome, Firefox, Safari, and Edge. Check our <a href="#" class="underline underline-offset-2 text-black dark:text-white">documentation</a> for additional information.
        </x-slot>
    </x-sf::accordionItem>
    <x-sf::accordionItem name="two">
        <x-slot:title>
            How can I contact customer support?
        </x-slot>
        Reach out to our dedicated support team via email at <a href="#" class="underline underline-offset-2 text-black dark:text-white">support@example.com</a> or call our toll-free number at 1-800-123-4567 during business hours.
    </x-sf::accordionItem>
</x-sf::accordion>
```

To purge the classes used by the package, add the following lines to your purge array in tailwind.config.js:
```
module.exports = {
  purge: {
    content: [
      './vendor/ades4827/sprintflow/resources/views/**/*.php',
      './storage/framework/views/*.php',
      './resources/views/**/*.blade.php',
    ],
  },
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {},
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
```