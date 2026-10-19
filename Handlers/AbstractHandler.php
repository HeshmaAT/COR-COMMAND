<?php

declare(strict_types=1);

namespace App\Handlers;
use App\Exceptions\ValidationFailedException;
use App\Services\Logger;
abstract class AbstractHandler
{
    private ?AbstractHandler $next = null;

    public function setNext(AbstractHandler $handler): AbstractHandler
    {
        $this->next = $handler;
        return $handler;
    }

    final public function handle(array $request): void
    {
        try {
            $this->validate($request);

        } catch (ValidationFailedException $e) {

            Logger::write(
                'Request Rejected: ' . $e->getMessage()
            );

            throw $e;
        }

        $this->next?->handle($request);
    }

    abstract protected function validate(array $request): void;
}