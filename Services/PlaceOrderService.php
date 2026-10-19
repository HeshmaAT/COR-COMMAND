<?php

declare(strict_types=1);

namespace App\Services;

use App\Commands\CommandInvoker;
use App\Commands\IOrderCommand;
use App\Handlers\AbstractHandler;
use App\Handlers\DiscountValidationHandler;
use App\Handlers\EmailValidationHandler;
use App\Handlers\ProductValidationHandler;
use App\Handlers\StockValidationHandler;
final class PlaceOrderService
{
    private AbstractHandler $chain;

    public function __construct(
        array $products,
        array $discounts,
        private readonly CommandInvoker $invoker
    ) {
        $this->chain = new EmailValidationHandler();

        $this->chain
            ->setNext(new ProductValidationHandler($products))
            ->setNext(new StockValidationHandler($products))
            ->setNext(new DiscountValidationHandler($discounts));
    }

    public function execute(array $request, IOrderCommand $command): void
    {
        $this->chain->handle($request);

        $this->invoker->execute($command);
    }
}