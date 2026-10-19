<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Exceptions\ValidationFailedException;
use App\Models\Order;
use App\Models\OrderStore;

final class DiscountOrderStatusHandler extends AbstractHandler
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

        if ($order->status !== Order::PENDING || $order->isPaid) {
            throw new ValidationFailedException(
                static::class,
                'Discounts can only be applied to pending and unpaid orders.'
            );
        }
    }
}