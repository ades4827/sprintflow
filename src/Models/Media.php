<?php
namespace Ades4827\Sprintflow\Models;

use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;
use Spatie\MediaLibrary\Support\UrlGenerator\UrlGeneratorFactory;

class Media extends BaseMedia
{
    public function getDownloadUrl(): string
    {
        return UrlGeneratorFactory::createForMedia($this)->getDownloadUrl();
    }

    public function getDownloadUrlAs(string $filename): string
    {
        return UrlGeneratorFactory::createForMedia($this)->getDownloadUrlAs($filename);
    }
}
