<?php

namespace Ades4827\Sprintflow\Support;

use Spatie\MediaLibrary\Support\UrlGenerator\DefaultUrlGenerator;

class CustomUrlGenerator extends DefaultUrlGenerator
{
    public function getUrl(): string
    {
        $url = route('media.getter', ['method' => 'serve', 'media' => $this->media->uuid, 'conversion' => $this->conversion]);

        return $this->versionUrl($url);
    }
}
