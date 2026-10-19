<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

final class ValidationFailedException extends RuntimeException
{
    public function __construct(
        public readonly string $handler,
        string $reason
    ) {
        parent::__construct("{$reason} From {$handler}");
    }
}