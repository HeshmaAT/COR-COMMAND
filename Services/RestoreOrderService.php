<?php

declare(strict_types=1);

namespace App\Services;

use App\Handlers\AbstractHandler;
use App\Handlers\ProductValidationHandler;
use App\Handlers\StockValidationHandler;
use App\Models\Order;
use App\Models\OrderStore;
use App\Models\Product;
use RuntimeException;

final class RestoreOrderService
{
    private AbstractHandler $chain;

    public function __construct(array $products)
    {
        $this->chain = new ProductValidationHandler($products);

        $this->chain->setNext(
            new StockValidationHandler($products)
        );
    }

    public function restore(Order $order): void
    {
        if ($order->status !== Order::CANCELLED || $order->isPaid) {
            throw new RuntimeException(
                'Only cancelled and unpaid orders can be restored.'
            );
        }

        $request = [
            'product_id' => $order->product->id,
            'quantity' => $order->quantity,
        ];

        $this->chain->handle($request);

        $order->product->quantity -= $order->quantity;

        $order->status = Order::PENDING;
    }
}