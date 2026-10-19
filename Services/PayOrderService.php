<?php

declare(strict_types=1);

namespace App\Services;

use App\Commands\CommandInvoker;
use App\Commands\IOrderCommand;
use App\Handlers\AbstractHandler;
use App\Handlers\EmailValidationHandler;
use App\Handlers\PayOrderStatusHandler;
use App\Handlers\PaymentValidationHandler;
use App\Models\OrderStore;

final class PayOrderService
{
    private AbstractHandler $chain;

    public function __construct(
        OrderStore $orders,
        private readonly CommandInvoker $invoker
    ) {
        $this->chain = new EmailValidationHandler();

        $this->chain
            ->setNext(new PayOrderStatusHandler($orders))
            ->setNext(new PaymentValidationHandler());
    }

    public function execute(array $request, IOrderCommand $command): void
    {
        $this->chain->handle($request);

        $this->invoker->execute($command);
    }
}