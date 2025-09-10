<div class="file-uploader border-2 border-dashed rounded-md mt-2 min-h-fit">
        @if($disabled)
        <div class="relative flex flex-col p-2 justify-center">
            <label class="flex flex-col items-center justify-center p-2 w-full cursor-pointer h-1/2 rounded hover:bg-slate-50">
                <h3>Non puoi caricare file</h3>
                <em class="italic text-slate-400">(richiedi maggiori permessi)</em>
            </label>
        </div>
        @else
        <div x-data="fileUpload('medias.uploads.{{$collection}}.{{$key}}')">
            <div class="relative flex flex-col p-2 justify-center"
                 x-on:drop="isDropping = false"
                 x-on:drop.prevent="handleFileDrop($event)"
                 x-on:dragover.prevent="isDropping = true"
                 x-on:dragleave.prevent="isDropping = false"
            >
                @if(!isset($medias['uploads'][$collection][$key]) || count($medias['uploads'][$collection][$key])==0 || $multiple )
                    <div class="absolute top-0 bottom-0 left-0 right-0 z-30 rounded-md flex items-center justify-center bg-blue-500 opacity-90"
                         x-show="isDropping"
                    >
                        <span class="text-3xl text-white">Trascina il file qui per caricarlo!</span>
                    </div>
                    <label class="flex flex-col items-center justify-center p-2 w-full cursor-pointer h-1/2 rounded hover:bg-slate-50"
                           for="file-upload-{{$collection}}-{{$key}}"
                    >
                        @if( !$multiple && isset($medias['collections'][$collection]) && count($medias['collections'][$collection])>0 )
                            <h3>Clicca per sostituire il file</h3>
                        @else
                            <h3>Clicca per caricare un file</h3>
                        @endif
                        <em class="italic text-slate-400">(oppure trascinalo qui)</em>
                        <div class="h-[2px] w-full mt-1">
                            <div
                                class="bg-blue-500 h-[2px]"
                                style="transition: width 1s"
                                :style="`width: ${progress}%;`"
                                x-show="isUploading"
                            >
                            </div>
                        </div>
                    </label>
                @endif
                @if(isset($medias['uploads'][$collection][$key]) && count($medias['uploads'][$collection][$key]))
                    <p class="font-bold">File pronti per essere caricati:</p>
                    <ul class="w-full">
                        @foreach($medias['uploads'][$collection][$key] as $key => $file)
                            <li class="my-1" wire:key="{{$collection.$key}}">
                                <span>{{$file->getClientOriginalName()}}</span>
                                <button class="text-red-500" @click.prevent="removeUpload('{{$file->getFilename()}}')">
                                    <i class="fa-light fa-trash"></i>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                @endif
                <input type="file" id="file-upload-{{$collection}}-{{$key}}" @if($multiple) multiple @endif @change="handleFileSelect" class="hidden" wire:key="{{ $key }}" />
            </div>
        </div>
        @once
            @push('scripts')
                <script>
                    function fileUpload(variable) {
                        return {
                            isDropping: false,
                            isUploading: false,
                            progress: 0,
                            handleFileSelect(event) {
                                if (event.target.files.length) {
                                    this.uploadFiles(event.target.files)
                                }
                            },
                            handleFileDrop(event) {
                                if (event.dataTransfer.files.length > 0) {
                                    this.uploadFiles(event.dataTransfer.files)
                                }
                            },
                            uploadFiles(files) {
                                const $this = this;
                                this.isUploading = true
                                @this.uploadMultiple(variable, files,
                                    function (success) {
                                        $this.isUploading = false
                                        $this.progress = 0
                                    },
                                    function(error) {
                                        console.log('error', error)
                                    },
                                    function (event) {
                                        $this.progress = event.detail.progress
                                    }
                                )
                            },
                            removeUpload(filename) {
                                @this.removeUpload(variable, filename)
                            },
                        }
                    }
                </script>
            @endpush
        @endonce
    @endif
</div>
