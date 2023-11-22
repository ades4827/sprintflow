<x-select label="Ruoli" wire:model.live="state.roles"
          :async-data="route('api.roles.index', [
                                        'guard' => 'web',
                                        'exclude' => 1,
                                    ])"
          option-label="readable_name"
          option-value="id"
          placeholder="Scegli i ruoli"
          multiselect >
</x-select>
