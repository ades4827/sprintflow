<?php

namespace Ades4827\Sprintflow\Logging;

use Monolog\Handler\RotatingFileHandler;

class MonthlyRotatingFileHandler extends RotatingFileHandler
{
    /**
     * Restituisce filename con formato: nome-YYYY-MM.ext
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

        // se dirname è '.', torna solo timed; altrimenti ricostruisci il path completo
        if ($dir === '.' || $dir === '') {
            return $timed;
        }

        return rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $timed;
    }
}
