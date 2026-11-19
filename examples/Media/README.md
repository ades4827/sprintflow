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

## ⚠️ Sicurezza: permessi di default

Senza personalizzazione il `FileAccessController` controlla **solo che ci sia un utente loggato** (guard `web` o `admin`).
Qualsiasi utente autenticato può quindi scaricare o visualizzare **qualsiasi** Media, anche se appartiene ad altri utenti
o è riservato agli admin.

- Con `'find_method' => 'id'` gli id sono sequenziali e facilmente enumerabili: **usare sempre `uuid`** se non si definisce `verifyPermission()`.
- Anche con `uuid`, chiunque sia loggato e conosca (o riceva) un link può aprire il file.

Se i file non sono pubblici per tutti gli utenti loggati, **è obbligatorio** definire `verifyPermission()`
in un controller personalizzato (vedi sotto) e verificare che l'utente abbia diritto a quel Media specifico
(es. proprietario del model collegato, ruolo, permesso).

## Personalizzazione dei permessi

`verifyPermission()` sostituisce completamente il controllo di default: se definito, il controllo "utente loggato"
non viene più eseguito, quindi deve includerlo se necessario.

Creare un controller di accesso personalizzato come da esempio da richiamare nelle config

```
<?php

namespace App\Http\Controllers;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FileAccessController extends \Ades4827\Sprintflow\Controllers\FileAccessController
{
    public function verifyPermission(string $method, Media $media) {
        // admin: accesso completo
        if (auth('admin')->user()) {
            return;
        }

        // utente: solo i Media dei propri model (adattare alla relazione reale)
        $user = auth()->user();
        if (! $user || $media->model?->user_id !== $user->id) {
            abort(403);
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