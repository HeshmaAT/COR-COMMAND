<?php

declare(strict_types=1);

namespace App\Models;

final class Order
{
    public const PENDING = 'pending';
    public const CANCELLED = 'cancelled';
    public const CONFIRMED = 'confirmed';

    public function __construct(
        public readonly int $id,
        public readonly Product $product,
        public readonly int $quantity,
        public float $total,
        public string $status = self::PENDING,
        public bool $isPaid = false,
        public ?Discount $discount = null,
    ) {}
}