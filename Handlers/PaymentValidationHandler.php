<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Exceptions\ValidationFailedException;

final class PaymentValidationHandler extends AbstractHandler
{
    protected function validate(array $request): void
    {
        if (($request['card_number'] ?? '') !== '5454') {
            throw new ValidationFailedException(
                static::class,
                'Invalid card number. Payment rejected.'
            );
        }
    }
}