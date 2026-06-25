@props([
    'action' => 'submit', // azione Livewire da lanciare
    'label' => __('sprintflow::view.save'),    // testo del bottone
    'icon' => 'save', // icona di default
    'disabled' => false,
])
<flux:button
    wire:click.prevent="{{ $action }}"
    variant="primary"
    wire:loading.attr="disabled"
    :disabled="$disabled"
>
    <div class="block sm:hidden">
        <span wire:loading.remove>@if($icon)<flux:icon :name="$icon" />@endif{{ $label }}</span>
        <flux:icon.loading wire:loading />
    </div>
    <div class="hidden sm:block">
		@if($icon)
            <flux:icon class="inline-block -mt-1" variant="micro" wire:loading.remove :name="$icon" />
            <flux:icon.loading wire:loading />
		@endif
		@if($label)
			<div class="inline-block ml-1">
				<span wire:loading.remove>{{ $label }}</span>
				<span wire:loading>{{ __('sprintflow::view.wait') }}...</span>
			</div>
		@endif
    </div>
</flux:button>
