<?php

declare(strict_types=1);

namespace App\Services;

use App\Commands\IOrderCommand;
use App\Handlers\AbstractHandler;
use App\Handlers\DiscountOrderStatusHandler;
use App\Handlers\DiscountValidationHandler;
use App\Handlers\EmailValidationHandler;
use App\Handlers\NoExistingDiscountHandler;
use App\Commands\CommandInvoker;
use App\Models\OrderStore;

final class ApplyDiscountService
{
    private AbstractHandler $chain;

    public function __construct(
        OrderStore $orders,
        array $discounts,
        private readonly CommandInvoker $invoker
    ) {
        $this->chain = new EmailValidationHandler();

        $this->chain
            ->setNext(new DiscountOrderStatusHandler($orders))
            ->setNext(new NoExistingDiscountHandler($orders))
            ->setNext(new DiscountValidationHandler($discounts));
    }

    public function execute(array $request, IOrderCommand $command): void
    {
        $this->chain->handle($request);

        $this->invoker->execute($command);
    }
}