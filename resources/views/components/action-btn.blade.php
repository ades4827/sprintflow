@props([
    'action' => 'submit', // azione Livewire da lanciare
    'label' => __('sprintflow::view.save'),    // testo del bottone
    'icon' => 'fa-solid fa-floppy-disk', // icona di default
    'icon_loading' => 'fa-solid fa-spinner fa-spin-pulse', // icona di default
    'class' => 'btn btn-primary', // classe CSS base
    'disabled' => false,
])
<button
    wire:click.prevent="{{ $action }}"
    class="{{ $class }}"
    wire:loading.attr="disabled"
    @if($disabled) disabled="disabled" @endif
>
    <div class="block sm:hidden">
        <span wire:loading.remove>@if($icon)<i class="{{ $icon }}"></i>@endif{{ $label }}</span>
        @if($icon_loading)
            <i wire:loading class="{{ $icon_loading }}"></i>
        @endif
    </div>
    <div class="hidden sm:block">
		@if($icon)
			<i wire:loading.remove class="{{ $icon }}"></i>
			@if($icon_loading)
				<i wire:loading class="{{ $icon_loading }}"></i>
			@endif
		@endif
		@if($label)
			<div class="ml-1">
				<span wire:loading.remove>{{ $label }}</span>
				<span wire:loading>{{ __('sprintflow::view.wait') }}...</span>
			</div>
		@endif
    </div>
</button>
