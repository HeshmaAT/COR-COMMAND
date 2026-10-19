<?php

declare(strict_types=1);

namespace App\Models;

final class Discount
{
    public function __construct(
        public string $code,
        public string $type,
        public float $value,
        public bool $isActive = true,
    ) {}
}