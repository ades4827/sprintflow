<?php

namespace Ades4827\Sprintflow\Controllers;

use App\Http\Controllers\Controller;
use InvalidArgumentException;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FileAccessController extends Controller
{
    public const string METHOD_DOWNLOAD = 'download';
    public const string METHOD_SERVE = 'serve';

    public function media(string $method, string $media, ?string $conversion = null)
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

        return $this->$method($media, $conversion);
    }

    public function download(Media $media, ?string $conversion = null)
    {
        if (method_exists($this, 'verifyPermission')) {
            $this->verifyPermission(__FUNCTION__, $media);
        } else {
            if (! auth()->user() && ! auth('admin')->user()) {
                abort('403');
            }
        }

        return response()->download($media->getPath(), $media->file_name, ['Cache-Control' => 'no-cache, must-revalidate']);
    }

    public function serve(Media $media, ?string $conversion = null)
    {
        if (method_exists($this, 'verifyPermission')) {
            $this->verifyPermission(__FUNCTION__, $media);
        } else {
            if (! auth()->user() && ! auth('admin')->user()) {
                abort('403');
            }
        }

        if ($conversion) {
            if (!$media->hasGeneratedConversion($conversion)) {
                abort('404', 'Conversion: '.$conversion.' not found');
            }
            return response()->file($media->getPath($conversion), ['Cache-Control' => 'no-cache, must-revalidate']);
        }

        return response()->file($media->getPath(), ['Cache-Control' => 'no-cache, must-revalidate']);
    }
}
