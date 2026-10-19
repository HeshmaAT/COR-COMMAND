<?php

declare(strict_types=1);

namespace App\Commands;

interface IUndoableCommand extends IOrderCommand
{
    public function undo(): void;
}