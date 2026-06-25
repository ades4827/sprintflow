<div class="@if(isset($class)) {{ $class }} @else mt-5 @endif">
    <x-auth-validation-errors class="mb-5" :errors="$errors" />
	<div class="grid grid-cols-3 gap-x-4 gap-y-2">
		<div>
			@if(method_exists($this, 'removeItem') && $entity)
				<flux:button wire:click="confirm('removeItem')" wire:loading.attr="disabled" icon="trash" variant="danger">
					<span class="hidden sm:block">{{ __('sprintflow::view.delete') }}</span>
				</flux:button>
			@endif
		</div>
		<div class="col-span-2 text-right flex justify-end gap-2">
			@if(isset($this->is_modal) && $this->is_modal)
				<flux:button wire:click="$dispatch('closeModal')" wire:loading.attr="disabled" icon="arrow-uturn-left">
					<span class="hidden sm:block">{{ __('sprintflow::view.cancel') }}</span>
				</flux:button>
			@elseif( isset($form_cancel_route) )
				<flux:button href="{{ route($form_cancel_route) }}" wire:loading.attr="disabled" icon="arrow-uturn-left">
					<span class="hidden sm:block">{{ __('sprintflow::view.cancel') }}</span>
				</flux:button>
			@elseif( isset($form_cancel_url) )
				<flux:button href="{{ $form_cancel_url }}" wire:loading.attr="disabled" icon="arrow-uturn-left">
					<span class="hidden sm:block">{{ __('sprintflow::view.cancel') }}</span>
				</flux:button>
			@endif
			@if( isset($save_and_edit) )
				<flux:button wire:click="submit('edit')" class="hidden sm:inline-flex" wire:loading.attr="disabled">
					<span wire:loading.remove>{{ __('sprintflow::view.save_and_edit') }}</span>
					<span wire:loading>{{ __('sprintflow::view.wait') }}...</span>
				</flux:button>
			@endif
			@if( !isset($hide_submit) )
				<x-sprintflow::flux.action-btn
						:label="isset($submit_label) ? $submit_label : __('sprintflow::view.save')"
						:icon="isset($submit_label) ? '' : 'save'"
				/>
			@endif
		</div>
	</div>
</div>
