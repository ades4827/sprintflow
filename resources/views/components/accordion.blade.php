<div id="{{ $uuid }}" x-data="{ selectedAccordionItem: '{{ $selectedItemName }}' }" {{ $attributes }}
     class="w-full divide-y divide-neutral-300 overflow-hidden rounded-md border border-neutral-300 text-neutral-600">
    {{ $slot }}
</div>
