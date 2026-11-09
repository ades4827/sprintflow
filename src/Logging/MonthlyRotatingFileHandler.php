<?php

namespace Ades4827\Sprintflow\Logging;

use Monolog\Handler\RotatingFileHandler;

class MonthlyRotatingFileHandler extends RotatingFileHandler
{
    /**
     * Returns the filename in the format: name-YYYY-MM.ext
     */
    protected function getTimedFilename(): string
    {
        $pathInfo = pathinfo($this->filename);

        $dir = $pathInfo['dirname'] ?? '';
        $name = $pathInfo['filename'] ?? $this->filename;
        $ext = isset($pathInfo['extension']) && $pathInfo['extension'] !== ''
            ? '.' . $pathInfo['extension']
            : '';

        $timed = $name . '-' . date('Y-m') . $ext;

        // if dirname is '.', return only the timed name; otherwise rebuild the full path
        if ($dir === '.' || $dir === '') {
            return $timed;
        }

        return rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $timed;
    }
}
