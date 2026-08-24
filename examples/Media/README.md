# Media serve routing

Aggiunge una rotta per il download di Media caricati tramite Spatie laravel-medialibrary aggiungendo dei permessi custom

## Per iniziare

Configurare la sezione media nelle config

```
'medias' => [
    'inject_route' => false,
    'file_access_controller' => \Ades4827\Sprintflow\Controllers\FileAccessController::class,
    'find_method' => 'uuid', // id or uuid
]
```

## Personalizzazione dei permessi

Creare un controller di accesso personalizzato come da esempio da richiamare nelle config

```
<?php

namespace App\Http\Controllers;

class FileAccessController extends \Ades4827\Sprintflow\Controllers\FileAccessController
{
    public function verifyPermission(string $method, Media $media) {
        // custom additional permission
        if (!auth('admin')->user()) {
            abort('403');
        }
    }
}

```

## Integrazione con il pacchetto media

Invece di utilizzare le url dei file generate dal pacchetto media (che vengono generate sulla base di un path pubblico) 
è possibile sovrascrivere l'url_generator nelle configurazioni media-library

```
/*
 * The fully qualified class name of the media model.
 */
'media_model' => \Ades4827\Sprintflow\Models\Media::class,

/*
 * When urls to files get generated, this class will be called. Use the default
 * if your files are stored locally above the site root or on s3.
 */
'url_generator' => Ades4827\Sprintflow\Support\CustomUrlGenerator::class,
```

in questo modo si può generare l'url direttamente dalla model Media

```
$model->getFirstMedia()->getUrl() // visualizza il documento
$model->getFirstMedia()->getDownloadUrl() // scarica il documento con il nome originale dell'upload
$model->getFirstMedia()->getDownloadUrlAs('pippo.pdf') // scarica il documento sovrascrivendo il nome utilizzato durante l'upload
```