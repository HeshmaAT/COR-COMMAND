<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Exceptions\ValidationFailedException;
use App\Models\Order;
use App\Models\OrderStore;

final class OrderStatusValidationHandler extends AbstractHandler
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

        if ($order->status === Order::CANCELLED) {
            throw new ValidationFailedException(
                static::class,
                'This order is already cancelled.'
            );
        }

        if ($order->status === Order::CONFIRMED || $order->isPaid) {
            throw new ValidationFailedException(
                static::class,
                'Confirmed or paid orders cannot be cancelled.'
            );
        }
    }
}