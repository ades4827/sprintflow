<form wire:submit.prevent="submit">

    @foreach($settings as $group_name => $group_settings)
        <div @if(!$loop->last) class="mb-6" @endif>
            <h1 class="text-lg font-medium mr-auto">{{ Str::ucfirst($group_name) }}</h1>
            <div class="grid grid-cols-1 md:grid-cols-{{ $group_settings['cols'] }} gap-x-4 gap-y-3 mt-2">
                @foreach($group_settings['properties'] as $setting_name => $setting)
                    @if( $this->fieldIsVisible($group_name, $setting_name) )
                        <div>
                            @if($setting['type'] === 'bool')
                                <div class="flex items-center">
                                    <x-checkbox label="{{ $setting['name'] }}" class="form-check-input"
                                                wire:model.live.debounce.500ms="settings.{{ $group_name }}.properties.{{ $setting_name }}.value" />
                                    <p class="mb-3">{{ $setting['description'] }}</p>
                                </div>
                            @elseif($setting['type'] === 'int')
                                <x-input type="number" step="1" label="{{ $setting['name'] }}" description="{{ $setting['description'] }}" wire:model.live.debounce.500ms="settings.{{ $group_name }}.properties.{{ $setting_name }}.value"/>
                            @elseif($setting['type'] === 'float')
                                <x-input type="number" step="0.01" label="{{ $setting['name'] }}" description="{{ $setting['description'] }}" wire:model.live.debounce.500ms="settings.{{ $group_name }}.properties.{{ $setting_name }}.value"/>
                            @elseif($setting['type'] === 'string')
                                <x-input label="{{ $setting['name'] }}" description="{{ $setting['description'] }}" wire:model.live.debounce.500ms="settings.{{ $group_name }}.properties.{{ $setting_name }}.value"/>
                            @elseif($setting['type'] === 'url')
                                <x-input type="url" label="{{ $setting['name'] }}" description="{{ $setting['description'] }}" wire:model.live.debounce.500ms="settings.{{ $group_name }}.properties.{{ $setting_name }}.value"/>
                            @elseif($setting['type'] === 'textarea')
                                <x-textarea label="{{ $setting['name'] }}" description="{{ $setting['description'] }}" wire:model.live.debounce.500ms="settings.{{ $group_name }}.properties.{{ $setting_name }}.value"/>
                            @elseif($setting['type'] === 'wireUiNativeSelect')
                                <x-native-select label="{{ $setting['name'] }}" description="{{ $setting['description'] }}"
                                                 wire:model.live.debounce.500ms="settings.{{ $group_name }}.properties.{{ $setting_name }}.value"
                                                 :options="$setting['wireUiNativeSelectOptions']"
                                                 option-label="name"
                                                 option-value="id"
                                                 placeholder="Seleziona un valore" />
                            @elseif($setting['type'] === 'wireUiSelect')
                                <x-select label="{{ $setting['name'] }}" description="{{ $setting['description'] }}"
                                          wire:model.live.debounce.500ms="settings.{{ $group_name }}.properties.{{ $setting_name }}.value"
                                          :async-data="route($setting['wireUiSelectRoute'])"
                                          option-label="name"
                                          option-value="id"
                                          placeholder="Seleziona un valore" />
                            @else
                                Missing type: {{ $setting['type'] }}
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endforeach

    @include('sprintflow::livewire.form-footer', ['entity' => null, 'hide_submit' => true])

</form>
