<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Exceptions\ValidationFailedException;

final class EmailValidationHandler extends AbstractHandler
{
    protected function validate(array $request): void
    {
        if (($request['email'] ?? '') !== 'heshmat@monstersgate.com') {
            throw new ValidationFailedException(
                static::class,
                'Invalid email. Access denied.'
            );
        }
    }
}