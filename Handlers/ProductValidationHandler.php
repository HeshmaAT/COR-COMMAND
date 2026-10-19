<?php

declare(strict_types=1);

namespace App\Handlers;

use App\Exceptions\ValidationFailedException;
use App\Models\Product;

final class ProductValidationHandler extends AbstractHandler
{
    public function __construct(
        private array $products
    ) {}

    protected function validate(array $request): void
    {
        $productId = $request['product_id'] ?? null;

        foreach ($this->products as $product) {
            if ($product instanceof Product && $product->id === $productId) {
                return;
            }
        }

        throw new ValidationFailedException(
            static::class,
            'The requested product does not exist.'
        );
    }
}