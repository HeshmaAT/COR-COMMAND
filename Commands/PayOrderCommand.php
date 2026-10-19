<?php

declare(strict_types=1);

namespace App\Commands;

use App\Models\Order;

final class PayOrderCommand implements IOrderCommand
{
    public function __construct(
        private readonly Order $order
    ) {}

    public function execute(): void
    {
        $this->order->isPaid = true;
        $this->order->status = Order::CONFIRMED;
    }
}