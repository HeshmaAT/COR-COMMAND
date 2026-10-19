<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Exceptions\ValidationFailedException;
use App\Models\Order;
use App\Models\OrderStore;

final class PayOrderStatusHandler extends AbstractHandler
{
    public function __construct(
        private readonly OrderStore $orders
    ) {}

    protected function validate(array $request): void
    {
        $order = $this->orders->find($request['order_id']);

        if ($order->status !== Order::PENDING || $order->isPaid) {
            throw new ValidationFailedException(
                static::class,
                'Only pending and unpaid orders can be paid.'
            );
        }
    }
}