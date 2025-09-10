<div>
    @if($multiple)
        <div class="grid grid-cols-1 gap-2">
            <div class="bg-slate-100 rounded">
                @include('sprintflow::livewire.medias.media-list', ['medias' => $medias, 'collection' => $collection, 'key' => ($key ?? 'single'), 'can_delete_all' => $can_delete_all, 'disabled' => $disabled])
            </div>
            <div>
                @include('sprintflow::livewire.medias.media-upload', ['medias' => $medias, 'collection' => $collection, 'key' => ($key ?? 'single'), 'multiple' => $multiple, 'disabled' => $disabled])
            </div>
        </div>
    @else
        <div class="flex flex-wrap">
            <div class="grow">
                @include('sprintflow::livewire.medias.media-upload', ['medias' => $medias, 'collection' => $collection, 'key' => ($key ?? 'single'), 'multiple' => $multiple, 'disabled' => $disabled])
            </div>
            <div>
                @include('sprintflow::livewire.medias.media-list', ['medias' => $medias, 'collection' => $collection, 'key' => ($key ?? 'single'), 'can_delete_all' => $can_delete_all, 'disabled' => $disabled])
            </div>
        </div>
    @endif
</div>
