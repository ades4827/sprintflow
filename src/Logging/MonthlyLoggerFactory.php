<?php

namespace Ades4827\Sprintflow\Logging;

use Monolog\Logger;
use Monolog\Formatter\LineFormatter;

class MonthlyLoggerFactory
{
    /**
     * Laravel invokes the class as a callable, passing the channel config.
     *
     * @param  array  $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        $path = $config['path'] ?? storage_path('logs/laravel.log');
        $level = $config['level'] ?? 'debug';
        // here "days" is interpreted as "number of months to keep"
        $maxFiles = $config['days'] ?? 12;

        $logger = new Logger($config['tap_name'] ?? 'monthly'); // any name

        // build the custom handler
        $handler = new MonthlyRotatingFileHandler(
            $path,
            (int) $maxFiles,
            Logger::toMonologLevel($level)
        );

        // (optional) clean format similar to Laravel's default
        $formatter = new LineFormatter(null, null, true, true);
        $handler->setFormatter($formatter);

        $logger->pushHandler($handler);

        return $logger;
    }
}
