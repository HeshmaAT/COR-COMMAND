<?php

declare(strict_types=1);

namespace App\Commands;

use App\Models\Discount;
use App\Models\Order;
use RuntimeException;

final class ApplyDiscountCommand implements IUndoableCommand
{
    private ?float $previousTotal = null;
    private bool $executed = false;

    public function __construct(
        private readonly Order $order,
        private readonly Discount $discount
    ) {}

    public function execute(): void
    {
        $this->previousTotal = $this->order->total;

        $discountAmount = match ($this->discount->type) {
            'percentage' => $this->order->total * ($this->discount->value / 100),
            'fixed' => $this->discount->value,
        };

        $this->order->total = max(
            0,
            $this->order->total - $discountAmount
        );

        $this->order->discount = $this->discount;
        $this->executed = true;
    }

    public function undo(): void
    {
        if (!$this->executed || $this->previousTotal === null) {
            throw new RuntimeException(
                'This discount cannot be undone.'
            );
        }

        if ($this->order->status !== Order::PENDING || $this->order->isPaid) {
            throw new RuntimeException(
                'Cannot undo a discount on a paid order.'
            );
        }

        if ($this->order->discount !== $this->discount) {
            throw new RuntimeException(
                'The order discount has changed.'
            );
        }

        $this->order->total = $this->previousTotal;
        $this->order->discount = null;

        $this->executed = false;
        $this->previousTotal = null;
    }
}