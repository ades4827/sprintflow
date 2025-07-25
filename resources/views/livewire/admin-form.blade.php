<form wire:submit.prevent="submit">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-input label="{{ __('sprintflow::fields.company') }}" wire:model="state.company" />
        <x-input label="{{ __('sprintflow::fields.name') }}" wire:model="state.name" />
        <x-input label="{{ __('sprintflow::fields.surname') }}" wire:model="state.surname" />
        <x-input type="email" label="{{ __('sprintflow::fields.email') }}" wire:model="state.email" />
        @if($model_id)
            <x-password label="{{ __('sprintflow::fields.new_password') }}" wire:model="state.password"
                        description="{{ __('sprintflow::fields.new_password_hint') }}" />
        @else
            <x-password label="{{ __('sprintflow::fields.password') }}" wire:model="state.password" />
        @endif

        <x-select label="{{ __('sprintflow::fields.roles') }}" wire:model.live="state.roles"
                  :options="$roles"
                  option-label="name"
                  option-value="id"
                  placeholder="{{ __('sprintflow::fields.select_roles') }}"
                  multiselect >
        </x-select>

        <div class="md:col-span-2">
            <x-checkbox label="{{ __('sprintflow::fields.user_enabled') }}" wire:model="state.is_enabled" />
        </div>
    </div>

    @include('sprintflow::livewire.form-footer', ['entity' => null, 'form_cancel_route' => 'admin.admins.index'])

</form>
