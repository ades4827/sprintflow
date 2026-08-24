<?php

namespace Ades4827\Sprintflow\Controllers;

use App\Http\Controllers\Controller;
use InvalidArgumentException;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FileAccessController extends Controller
{
    public const string METHOD_DOWNLOAD = 'download';
    public const string METHOD_SERVE = 'serve';

    public function media(string $method, string $media, ?string $filename = null, ?string $conversion = null)
    {
        if (!in_array($method, [self::METHOD_DOWNLOAD, self::METHOD_SERVE], true)) {
            throw new InvalidArgumentException("Invalid method: $method");
        }

        if(config('sprintflow.medias.find_method') == 'uuid') {
            $media = config('media-library.media_model')::findByUuid($media);
        } elseif(config('sprintflow.medias.find_method') == 'id') {
            $media = config('media-library.media_model')::find($media);
        } else {
            throw new InvalidArgumentException("Invalid find_method: ".config('sprintflow.medias.find_method'));
        }

        if(!$media) {
            abort('404', "Media not found");
        }

        return $this->$method(media: $media, filename: $filename, conversion: $conversion);
    }

    public function download(Media $media, ?string $filename = null, ?string $conversion = null)
    {
        if (method_exists($this, 'verifyPermission')) {
            $this->verifyPermission(__FUNCTION__, $media);
        } else {
            if (! auth()->user() && ! auth('admin')->user()) {
                abort('403');
            }
        }

        if($filename === null) {
            $filename = $media->file_name;
        }

        return response()->download($media->getPath(), $filename, ['Cache-Control' => 'no-cache, must-revalidate']);
    }

    public function serve(Media $media, ?string $filename = null, ?string $conversion = null)
    {
        if (method_exists($this, 'verifyPermission')) {
            $this->verifyPermission(__FUNCTION__, $media);
        } else {
            if (! auth()->user() && ! auth('admin')->user()) {
                abort('403');
            }
        }

        $path = $media->getPath();
        if ($conversion) {
            if (!$media->hasGeneratedConversion($conversion)) {
                abort('404', 'Conversion: '.$conversion.' not found');
            }

            $path = $media->getPath($conversion);
        }

        return response()->file($path, [
            'Cache-Control' => 'no-cache, must-revalidate',
            'Content-Disposition' => 'inline; filename="' . $media->file_name . '"',
        ]);
    }
}
