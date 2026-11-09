<?php

namespace Ades4827\Sprintflow\Controllers;

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

        $path = $this->resolvePath($media, $conversion);

        if ($filename === null) {
            $filename = $this->defaultFilename($media, $path, $conversion);
        }

        return response()->download($path, $filename, ['Cache-Control' => 'no-cache, must-revalidate']);
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

        $path = $this->resolvePath($media, $conversion);

        return response()->file($path, [
            'Cache-Control' => 'no-cache, must-revalidate',
            'Content-Disposition' => 'inline; filename="' . $this->defaultFilename($media, $path, $conversion) . '"',
        ]);
    }

    /**
     * Path of the original file, or of the given conversion (404 if not generated).
     */
    protected function resolvePath(Media $media, ?string $conversion = null): string
    {
        if (! $conversion) {
            return $media->getPath();
        }

        if (! $media->hasGeneratedConversion($conversion)) {
            abort('404', 'Conversion: '.$conversion.' not found');
        }

        return $media->getPath($conversion);
    }

    /**
     * Original file name, or the conversion file name (it may have a different extension).
     */
    protected function defaultFilename(Media $media, string $path, ?string $conversion = null): string
    {
        return $conversion ? basename($path) : $media->file_name;
    }
}
