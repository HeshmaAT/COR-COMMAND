<?php

declare(strict_types=1);

namespace App\Commands;

use App\Models\Order;
use App\Services\RestoreOrderService;
use RuntimeException;

final class CancelOrderCommand implements IUndoableCommand
{
    private bool $cancelled = false;

    public function __construct(
        private readonly Order $order,
        private readonly RestoreOrderService $restoreService,
    ) {}

    public function execute(): void
    {
        $this->order->status = Order::CANCELLED;

        $this->order->product->quantity += $this->order->quantity;

        $this->cancelled = true;
    }

    public function undo(): void
    {
        if (!$this->cancelled) {
            throw new RuntimeException(
                'This cancellation cannot be undone.'
            );
        }

        $this->restoreService->restore($this->order);

        $this->cancelled = false;
    }
}