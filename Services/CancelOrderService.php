<?php

declare(strict_types=1);

namespace App\Services;

use App\Commands\IOrderCommand;
use App\Handlers\AbstractHandler;
use App\Handlers\EmailValidationHandler;
use App\Handlers\OrderStatusValidationHandler;
use App\Commands\CommandInvoker;
use App\Models\OrderStore;

final class CancelOrderService
{
    private AbstractHandler $chain;

    public function __construct(
        OrderStore $orders,
        private readonly CommandInvoker $invoker,
    ) {
        $this->chain = new EmailValidationHandler();

        $this->chain->setNext(
            new OrderStatusValidationHandler($orders)
        );
    }

    public function execute(array $request, IOrderCommand $command): void
    {
        $this->chain->handle($request);

        $this->invoker->execute($command);
    }
}