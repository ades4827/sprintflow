# Log Rotate

Add missing monthly log rotate to Laravel

Use example:

```
config/logging.php

'channels' => [
    'custom_channel' => [
            'driver' => 'custom',
            'via' => Ades4827\Sprintflow\Logging\MonthlyLoggerFactory::class,
            'days' => 12,
            'path' => storage_path('logs/custom_channel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
    ],
],
```