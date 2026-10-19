<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Exceptions\ValidationFailedException;
use App\Models\OrderStore;

final class NoExistingDiscountHandler extends AbstractHandler
{
    public function __construct(
        private readonly OrderStore $orders
    ) {}

    protected function validate(array $request): void
    {
        $orderId = $request['order_id'] ?? null;

        if (!is_int($orderId) || $orderId <= 0) {
            throw new ValidationFailedException(
                static::class,
                'A valid order ID is required.'
            );
        }

        $order = $this->orders->find($orderId);

        if ($order->discount !== null) {
            throw new ValidationFailedException(
                static::class,
                'This order already has a discount. Undo it before applying another one.'
            );
        }
    }
}