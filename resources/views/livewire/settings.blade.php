<form wire:submit.prevent="submit">

    @foreach($settings as $group_name => $group_settings)
        <div @if(!$loop->last) class="mb-6" @endif>
            <h1 class="text-lg font-medium mr-auto">{{ Str::ucfirst($group_name) }}</h1>
            <div class="grid grid-cols-1 md:grid-cols-{{ $group_settings['cols'] }} gap-x-4 gap-y-3 mt-2">
                @foreach($group_settings['properties'] as $setting_name => $setting)
                    @if( $this->fieldIsVisible($group_name, $setting_name) )
                        <div>
                            @if($this->livewire_component_library == 'wireui')
                                @include('sprintflow::livewire.settings-fields-wireui')
                            @elseif($this->livewire_component_library == 'fluxui')
                                @include('sprintflow::livewire.settings-fields-fluxui')
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endforeach

    @include('sprintflow::livewire.form-footer', ['entity' => null, 'hide_submit' => true])

</form>
