@isset($medias['collections'])
    <div class="flex flex-wrap">
        @foreach($medias['collections'][$key][$collection] as $single_media)
            <div class="w-20 h-20 relative image-fit m-2 zoom-in bg-slate-50">
                {{--Show thumb, or original asset or not found--}}
                @if( is_array($single_media) && isset($single_media['conversions']) )
                    @if(isset($single_media['conversions']['thumb']))
                        <a href="{{ $single_media['original']['url'] }}" class="cursor-auto border rounded block w-20 h-20 overflow-hidden" target="_blank">
                            <img class="rounded-md" alt="" src="{{ $single_media['conversions']['thumb']['url'] }}">
                        </a>
                    @endif
                @elseif( is_array($single_media) && isset($single_media['original']))
                    <a href="{{ $single_media['original']['url'] }}"
                       class="cursor-auto border rounded w-20 h-20 overflow-hidden grid place-content-center text-center" target="_blank">
                        @if(isset($single_media['is_image']) && $single_media['is_image'])
                            <img class="rounded-md" alt="" src="{{ $single_media['original']['url'] }}">
                        @else
                            <div class="text-lg tooltip" data-placement="bottom" title="{{ $single_media['mime_type'] }}">
                                <i class="fa-solid fa-file text-primary"></i>
                            </div>
                        @endif
                    </a>
                @else
                    <div class="w-20 h-20 rounded-md flex items-center justify-center text-center text-xs">
                        Immagine non trovata
                    </div>
                @endif

                @if(!$disabled)
                    {{--Show remove button only for additional image--}}
                    @if ( !$loop->first || $can_delete_all )
                        <button class="w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-danger right-0 top-0 -mr-2 -mt-2 tooltip-loaded cursor-pointer"
                                wire:click.prevent="removeMedia('{{$single_media['id']}}', '{{$collection}}')">
                            <i class="fa-light fa-xmark"></i>
                        </button>
                    @endif
                @endif
            </div>
        @endforeach
    </div>
@endisset
