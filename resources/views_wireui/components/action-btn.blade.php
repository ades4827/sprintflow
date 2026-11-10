@props([
    'action' => 'submit', // Livewire action to call
    'label' => __('sprintflow::view.save'),    // button label
    'icon' => 'fa-solid fa-floppy-disk', // default icon
    'icon_loading' => 'fa-solid fa-spinner fa-spin-pulse', // default loading icon
    'class' => 'btn btn-primary', // base CSS class
    'disabled' => false,
])
<button
		wire:click.prevent="{{ $action }}"
		class="{{ $class }}"
		wire:loading.attr="disabled"
		@if($disabled) disabled="disabled" @endif
>
	<div class="block sm:hidden">
		<span wire:loading.delay.remove>
            @if($icon)
                <i class="{{ $icon }}"></i>
            @endif
            @if($label)
                <div class="inline-block ml-1">
                    <span wire:loading.delay.remove>{{ $label }}</span>
                    <span wire:loading.delay>{{ __('sprintflow::view.wait') }}...</span>
                </div>
            @endif
        </span>
		@if($icon_loading)
			<i wire:loading.delay class="{{ $icon_loading }}"></i>
		@endif
	</div>
	<div class="hidden sm:block">
		@if($icon)
			<i wire:loading.delay.remove class="{{ $icon }}"></i>
			@if($icon_loading)
				<i wire:loading.delay class="{{ $icon_loading }}"></i>
			@endif
		@endif
		@if($label)
			<div class="inline-block ml-1">
				<span wire:loading.delay.remove>{{ $label }}</span>
				<span wire:loading.delay>{{ __('sprintflow::view.wait') }}...</span>
			</div>
		@endif
	</div>
</button>
