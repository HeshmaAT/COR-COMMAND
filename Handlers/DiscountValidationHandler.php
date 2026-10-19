<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Exceptions\ValidationFailedException;
use App\Models\Discount;

final class DiscountValidationHandler extends AbstractHandler
{
    public function __construct(
        private array $discounts
    ) {}

    protected function validate(array $request): void
    {
        $code = $request['discount_code'] ?? null;

        if ($code === null || $code === '') {
            if (($request['action'] ?? null) === 'apply_discount') {
                throw new ValidationFailedException(
                    static::class,
                    'Discount code is required.'
                );
            }

            return;
        }

        if (!is_string($code)) {
            throw new ValidationFailedException(
                static::class,
                'Invalid discount code.'
            );
        }

        foreach ($this->discounts as $discount) {
            if (
                $discount instanceof Discount &&
                strcasecmp($discount->code, $code) === 0
            ) {
                if (!$discount->isActive) {
                    throw new ValidationFailedException(
                        static::class,
                        'Discount code is inactive.'
                    );
                }

                return;
            }
        }

        throw new ValidationFailedException(
            static::class,
            'Discount code does not exist.'
        );
    }
}