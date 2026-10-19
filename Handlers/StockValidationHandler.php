<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Exceptions\ValidationFailedException;
use App\Models\Product;

final class StockValidationHandler extends AbstractHandler
{
    public function __construct(
        private array $products
    ) {}

    protected function validate(array $request): void
    {
        $productId = $request['product_id'] ?? null;
        $quantity = $request['quantity'] ?? null;

        if (!is_int($quantity) || $quantity <= 0) {
            throw new ValidationFailedException(
                static::class,
                'Quantity must be greater than zero.'
            );
        }

        foreach ($this->products as $product) {
            if (!$product instanceof Product || $product->id !== $productId) {
                continue;
            }

            if ($product->quantity < $quantity) {
                throw new ValidationFailedException(
                    static::class,
                    'Insufficient product stock.'
                );
            }

            return;
        }

        throw new ValidationFailedException(
            static::class,
            'Product not found.'
        );
    }
}