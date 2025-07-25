<form wire:submit.prevent="submit">

    <p>{{ __('sprintflow::fields.new_password_hint') }}</p>
    <div class="grid grid-cols-1 gap-4 mt-4">
        <x-input type="password" label="{{ __('sprintflow::fields.current_password') }}" wire:model="state.current_password" />

        <x-input type="password" label="{{ __('sprintflow::fields.new_password') }}" wire:model="state.password" />
        <x-input type="password" label="{{ __('sprintflow::fields.repeat_password') }}" wire:model="state.password_confirmation"
                 description="{{ __('sprintflow::fields.repeat_password_hint') }}" />
    </div>

    @include('sprintflow::livewire.form-footer', ['entity' => null])

</form>
