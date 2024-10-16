<div class="mt-5">
    @if($errors->any())
        <div>
            <strong class="block text-danger">{{ __('sprintflow::view.attention') }}:</strong>
            @foreach ($errors->all() as $error)
                <small class="block text-danger">{{ $error }}</small>
            @endforeach
        </div>
    @endif
    <div class="grid grid-cols-3 gap-x-4 gap-y-2 pt-5">
        <div>
            @if(method_exists($this, 'removeItem') && $entity)
                <a wire:click="confirm('removeItem')" wire:loading.attr="disabled" class="btn btn-danger mr-1">
                    <i class="fa-solid fa-trash"></i><span class="hidden sm:block">{{ __('sprintflow::view.delete') }}</span>
                </a>
            @endif
        </div>
        <div class="col-span-2 text-right">
            @if(isset($this->is_modal) && $this->is_modal)
                <a wire:click="$dispatch('closeModal')" wire:loading.attr="disabled" class="btn btn-outline-secondary mr-1">
                    <i class="fa-solid fa-rotate-left"></i>
                    <span class="hidden sm:block">{{ __('sprintflow::view.cancel') }}</span>
                </a>
            @elseif( isset($form_cancel_route) )
                <a href="{{ route($form_cancel_route) }}" wire:loading.attr="disabled" class="btn btn-outline-secondary mr-1">
                    <i class="fa-solid fa-rotate-left"></i>
                    <span class="hidden sm:block">{{ __('sprintflow::view.cancel') }}</span>
                </a>
            @elseif( isset($form_cancel_url) )
                <a href="{{ $form_cancel_url }}" wire:loading.attr="disabled" class="btn btn-outline-secondary mr-1">
                    <i class="fa-solid fa-rotate-left"></i>
                    <span class="hidden sm:block">{{ __('sprintflow::view.cancel') }}</span>
                </a>
            @endif
            @if( isset($save_and_edit) )
                <a wire:click="submit('edit')" class="btn btn-primary hidden sm:inline-flex" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ __('sprintflow::view.save_and_edit') }}</span>
                    <span wire:loading>{{ __('sprintflow::view.wait') }}...</span>
                </a>
            @endif
            @if( !isset($hide_submit) )
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                    <div class="block sm:hidden">
                        <span wire:loading.remove><i class="fa-solid fa-floppy-disk"></i> {{ __('sprintflow::view.save') }}</span>
                        <i wire:loading class="fa-solid fa-spinner fa-spin-pulse"></i>
                    </div>
                    <div class="hidden sm:block">
                        <i class="fa-solid fa-floppy-disk mr-1"></i>
                        <span wire:loading.remove>{{ __('sprintflow::view.save') }}</span>
                        <span wire:loading>{{ __('sprintflow::view.wait') }}...</span>
                    </div>
                </button>
            @endif
        </div>
    </div>
</div>
