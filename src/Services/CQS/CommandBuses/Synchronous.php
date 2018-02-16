<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\CommandBuses;

use Onyx\Services\CQS\CommandBus;
use Onyx\Services\CQS\Command;
use Onyx\Services\CQS\CommandHandlerProvider;

class Synchronous implements CommandBus
{
    private
        $commandHandlerProvider;

    public function __construct(CommandHandlerProvider $commandHandlerProvider)
    {
        $this->commandHandlerProvider= $commandHandlerProvider;
    }

    public function send(Command $command): void
    {
        $handler = $this->commandHandlerProvider->findCommandHandlerFor($command);
        $handler->handle($command);
    }
}
