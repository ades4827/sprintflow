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