# Livewire Media upload

Guida per utilizzare un upload facilitato di media tramite Livewire utilizzando la libreria Spatie Medialibrary

## Requisiti

Come prima cosa va installata la libreria seguendo questa guida: https://spatie.be/docs/laravel-medialibrary/v11/installation-setup

## Utilizzo

Personalizzare le model come da esempio per funzionare con Medialibrary

```
<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Model extends BaseModel implements HasMedia
{
    use InteractsWithMedia;

    public const COLLECTION_ATTACHMENT = 'attachment';
    public const COLLECTION_DEM_HEADER = 'dem_header';
    
    public function registerMediaCollections(): void
    {
        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Common_types

        $this->addMediaCollection(self::COLLECTION_DEM_HEADER)
            ->acceptsMimeTypes(['image/jpeg', 'image/png'])
            ->useDisk('dem')
            ->onlyKeepLatest(1);
            
        $this->addMediaCollection(self::COLLECTION_ATTACHMENT)
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'application/pdf'])
            ->useDisk('article_attachment')
            ->onlyKeepLatest(1);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::FillMax, 150, 150)
            ->quality(80)
            ->nonQueued();
    }
}

```

Nel componente Livewire inserire i Trait: LivewireMediaTrait e LivewireFileValidationTrait e seguire il seguente esempio per caricare i media e validarli

```
<?php

namespace App\Livewire;

use Ades4827\Sprintflow\Traits\LivewireFileValidationTrait;
use Ades4827\Sprintflow\Traits\LivewireMediaTrait;
use Livewire\Component;

class ModelForm extends Component
{
    use LivewireFileValidationTrait, LivewireMediaTrait;
    
    public array $medias;
    
    protected function rules()
    {
        $rules = [
            'state.name' => 'required|max:100',
        ];

        if(!$this->model_id) {
            $rules['medias.uploads.'.Model::COLLECTION_ATTACHMENT] = 'required|min:1';
        }

        return $rules;
    }
    
    public function mount()
    {
        $this->initMedia([
            Model::COLLECTION_ATTACHMENT,
        ]);
        
        if ($this->model_id) {
            $model = Model::findOrFail($this->model_id);
            $this->getMedia($model, ['collections' => [Model::COLLECTION_ATTACHMENT]]);
        }
    }
    
    public function updated($name, $value)
    {
        $exploded_name = explode('.', $name);
        if ($exploded_name[0] == 'medias' && $exploded_name[1] == 'uploads') {
            $this->validateMediaCollections(new Model, Model::COLLECTION_ATTACHMENT);
        }
    }
    
    public function submit()
    {
        $this->validate();
        $this->validateCollections(new Model, Model::COLLECTION_ATTACHMENT);

        // create or update Model
        $model = Model::findOrFail($this->model_id);
        $model->save();

        foreach ($this->medias['uploads'] as $collection_name => $positions) {
            foreach ($positions as $uploads) {
                foreach ($uploads as $upload) {
                    $model->addMedia($upload->path())->toMediaCollection($collection_name);
                }
            }
        }
    }
}
```

nel template del componente:

```
<div>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Allegato</label>
    @include('sprintflow::livewire.medias.media', [
        'medias' => $this->medias, 
        'collection' => \App\Models\Model::COLLECTION_ATTACHMENT, 
        'key' => $model_id,
        'multiple' => false,
        'can_delete_all' => true,
        'disabled' => false
    ])
</div>
```