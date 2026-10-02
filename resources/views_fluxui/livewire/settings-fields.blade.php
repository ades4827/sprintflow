@if($setting['type'] === 'bool')
    <div class="flex items-center">
        <flux:checkbox label="{{ $setting['name'] }}" class="form-check-input"
                    wire:model.live.debounce.500ms="settings.{{ $group_name }}.properties.{{ $setting_name }}.value" />
        <p class="mb-3">{{ $setting['description'] }}</p>
    </div>
@elseif($setting['type'] === 'int')
    <flux:input type="number" step="1" label="{{ $setting['name'] }}" :description="$setting['description'] ?? null" wire:model.live.debounce.500ms="settings.{{ $group_name }}.properties.{{ $setting_name }}.value"/>
@elseif($setting['type'] === 'float')
    <flux:input type="number" step="0.01" label="{{ $setting['name'] }}" :description="$setting['description'] ?? null" wire:model.live.debounce.500ms="settings.{{ $group_name }}.properties.{{ $setting_name }}.value"/>
@elseif($setting['type'] === 'string')
    <flux:input label="{{ $setting['name'] }}" :description="$setting['description'] ?? null" wire:model.live.debounce.500ms="settings.{{ $group_name }}.properties.{{ $setting_name }}.value"/>
@elseif($setting['type'] === 'url')
    <flux:input type="url" label="{{ $setting['name'] }}" :description="$setting['description'] ?? null" wire:model.live.debounce.500ms="settings.{{ $group_name }}.properties.{{ $setting_name }}.value"/>
@elseif($setting['type'] === 'textarea')
    <flux:textarea label="{{ $setting['name'] }}" :description="$setting['description'] ?? null" wire:model.live.debounce.500ms="settings.{{ $group_name }}.properties.{{ $setting_name }}.value"/>
@else
    Missing type: {{ $setting['type'] }}
@endif
