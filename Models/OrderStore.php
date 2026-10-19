<?php

declare(strict_types=1);

namespace App\Models;

use RuntimeException;

final class OrderStore
{
    private array $orders = [];

    private int $nextId = 1;

    public function nextId(): int
    {
        return $this->nextId++;
    }

    public function add(Order $order): void
    {
        $this->orders[$order->id] = $order;
    }

    public function find(int $id): Order
    {
        return $this->orders[$id]
            ?? throw new RuntimeException("Order #{$id} not found.");
    }

    public function all(): array
    {
        return array_values($this->orders);
    }
}