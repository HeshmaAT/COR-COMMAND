<?php

declare(strict_types=1);

namespace App\Commands;

interface IOrderCommand
{
    public function execute(): void;
}