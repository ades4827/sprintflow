<?php

namespace Ades4827\Sprintflow\Logging;

use Monolog\Logger;
use Monolog\Formatter\LineFormatter;

class MonthlyLoggerFactory
{
    /**
     * Laravel invoca la classe come callable passando la config del canale.
     *
     * @param  array  $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        $path = $config['path'] ?? storage_path('logs/laravel.log');
        $level = $config['level'] ?? 'debug';
        // days qui lo interpretiamo come "numero di mesi da mantenere"
        $maxFiles = $config['days'] ?? 12;

        $logger = new Logger($config['tap_name'] ?? 'monthly'); // nome qualsiasi

        // costruiamo il handler personalizzato
        $handler = new MonthlyRotatingFileHandler(
            $path,
            (int) $maxFiles,
            Logger::toMonologLevel($level)
        );

        // (opzionale) formato pulito simile al default di Laravel
        $formatter = new LineFormatter(null, null, true, true);
        $handler->setFormatter($formatter);

        $logger->pushHandler($handler);

        return $logger;
    }
}
