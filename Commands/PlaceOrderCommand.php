<?php

declare(strict_types=1);

namespace App\Commands;

use App\Models\Order;
use App\Models\OrderStore;
use App\Models\Product;
use RuntimeException;

final class PlaceOrderCommand implements IOrderCommand
{
    private ?Order $createdOrder = null;

    public function __construct(
        private readonly OrderStore $orders,
        private readonly Product $product,
        private readonly int $quantity,
    ) {}

    public function execute(): void
    {
        $total = $this->product->price * $this->quantity;

        $order = new Order(
            id: $this->orders->nextId(),
            product: $this->product,
            quantity: $this->quantity,
            total: $total,
        );

        $this->product->quantity -= $this->quantity;

        $this->orders->add($order);

        $this->createdOrder = $order;
    }

    public function getCreatedOrder(): ?Order
    {
        return $this->createdOrder;
    }
}