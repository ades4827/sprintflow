<div {{ $attributes }} class="divide-y divide-neutral-300" x-data="{ open: openedAccordionItem == '{{ $name }}' }">
    <button id="controlsAccordionItem{{ $name }}" type="button"
            {{ $title->attributes->merge(['class' => 'flex w-full items-center gap-4 p-3 underline-offset-2 hover:bg-neutral-50/75
            focus-visible:bg-neutral-50/75 focus-visible:underline focus-visible:outline-none']) }}
            aria-controls="accordionItem{{ $name }}" @click="open = !open; openedAccordionItem = '{{ $name }}'"
            :class="((multiopen === true) ? open==1 : openedAccordionItem === '{{ $name }}') ? 'text-onSurfaceStrong font-bold'  : 'text-onSurface font-medium'"
            :aria-expanded="((multiopen === true) ? open==1 : openedAccordionItem === '{{ $name }}') ? 'true' : 'false'">
        {{ $title ?? 'Missing title' }}
    </button>
    <div x-cloak x-show="(multiopen === true) ? open==1 : openedAccordionItem === '{{ $name }}'" id="accordionItem{{ $name }}" role="region"
         aria-labelledby="controlsAccordionItem{{ $name }}" x-collapse>

        @if ($slot->isEmpty())
            <div {{ $body->attributes }}>
                {{ $body }}
            </div>
        @else
            <div class="p-4">
                {{ $slot }}
            </div>
        @endif
    </div>
</div>
