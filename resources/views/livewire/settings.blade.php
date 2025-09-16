<form wire:submit.prevent="submit">

    @foreach($settings as $group_name => $group_settings)
        <div @if(!$loop->last) class="mb-5" @endif>
            <h1 class="text-lg font-medium mr-auto">{{ Str::ucfirst($group_name) }}</h1>
            @foreach($group_settings as $setting_name => $setting)
                @if( $this->fieldIsVisible($group_name, $setting_name) )
                    @if($setting['type'] === 'bool')
                        <div class="form-check my-2">
                            <x-checkbox label="{{ $setting['name'] }}" class="form-check-input"
                                        wire:model.live.debounce.500ms="settings.{{ $group_name }}.{{ $setting_name }}.value" />
                        </div>
                        <p class="mb-3">{{ $setting['description'] }}</p>
                    @elseif($setting['type'] === 'int')
                        <div class="form-group my-2">
                            <x-input type="number" step="1" label="{{ $setting['name'] }}" description="{{ $setting['description'] }}" wire:model.live.debounce.500ms="settings.{{ $group_name }}.{{ $setting_name }}.value"/>
                        </div>
                    @elseif($setting['type'] === 'float')
                        <div class="form-group my-2">
                            <x-input type="number" step="0.01" label="{{ $setting['name'] }}" description="{{ $setting['description'] }}" wire:model.live.debounce.500ms="settings.{{ $group_name }}.{{ $setting_name }}.value"/>
                        </div>
                    @elseif($setting['type'] === 'string')
                        <div class="form-group my-2">
                            <x-input label="{{ $setting['name'] }}" description="{{ $setting['description'] }}" wire:model.live.debounce.500ms="settings.{{ $group_name }}.{{ $setting_name }}.value"/>
                        </div>
                    @elseif($setting['type'] === 'url')
                        <div class="form-group my-2">
                            <x-input type="url" label="{{ $setting['name'] }}" description="{{ $setting['description'] }}" wire:model.live.debounce.500ms="settings.{{ $group_name }}.{{ $setting_name }}.value"/>
                        </div>
                    @elseif($setting['type'] === 'textarea')
                        <div class="form-group my-2">
                            <x-textarea label="{{ $setting['name'] }}" description="{{ $setting['description'] }}" wire:model.live.debounce.500ms="settings.{{ $group_name }}.{{ $setting_name }}.value"/>
                        </div>
                    @elseif($setting['type'] === 'wireUiNativeSelect')
                        <div class="form-group my-2">
                            <x-native-select label="{{ $setting['name'] }}" description="{{ $setting['description'] }}"
                                             wire:model.live.debounce.500ms="settings.{{ $group_name }}.{{ $setting_name }}.value"
                                             :options="$setting['wireUiNativeSelectOptions']"
                                             option-label="name"
                                             option-value="id"
                                             placeholder="Seleziona un valore" />
                        </div>
                    @elseif($setting['type'] === 'wireUiSelect')
                        <div class="form-group my-2">
                            <x-select label="{{ $setting['name'] }}" description="{{ $setting['description'] }}"
                                      wire:model.live.debounce.500ms="settings.{{ $group_name }}.{{ $setting_name }}.value"
                                      :async-data="route($setting['wireUiSelectRoute'])"
                                      option-label="name"
                                      option-value="id"
                                      placeholder="Seleziona un valore" />
                        </div>
                    @else
                        <div class="form-group my-2">
                            Missing type: {{ $setting['type'] }}
                        </div>
                    @endif
                @endif
            @endforeach
        </div>
    @endforeach

    @include('sprintflow::livewire.form-footer', ['entity' => null, 'hide_submit' => true])

</form>