<?php

declare(strict_types=1);

namespace App\Commands;

use App\Services\Logger;
use RuntimeException;

final class CommandInvoker
{
    private array $history = [];

    public function execute(IOrderCommand $command): void
    {
        $command->execute();

        $this->history[] = [
            'command' => $command,
            'undone' => false,
        ];

        Logger::write(
            'Command Executed: ' . get_class($command)
        );
    }

    public function undo(): void
    {
        if (empty($this->history)) {
            throw new RuntimeException('No commands available to undo.');
        }

        $lastIndex = array_key_last($this->history);
        $lastAction = $this->history[$lastIndex];

        $command = $lastAction['command'];

        if ($lastAction['undone']) {
            throw new RuntimeException(
                'The last command has already been undone.'
            );
        }

        if (!$command instanceof IUndoableCommand) {
            throw new RuntimeException(
                sprintf(
                    '%s does not support undo.',
                    (new \ReflectionClass($command))->getShortName()
                )
            );
        }

        $command->undo();

        $this->history[$lastIndex]['undone'] = true;

        Logger::write(
            'Command Executed: ' . get_class($command)
        );
    }

    public function getHistory(): array
    {
        return $this->history;
    }
}