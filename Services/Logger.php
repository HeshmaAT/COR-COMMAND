<?php

declare(strict_types=1);

namespace App\Services;

final class Logger
{
    public static function write(string $message): void
    {
        $date = date('Y-m-d H:i:s');

        file_put_contents(__DIR__ . '/../logs/app.log',
            "[{$date}] {$message}" . PHP_EOL,
            FILE_APPEND
        );
    }
}