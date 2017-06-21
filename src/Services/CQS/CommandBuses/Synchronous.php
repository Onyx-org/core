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

        if($handler->accept($command))
        {
            $handler->handle($command);

            return;
        }

        throw new \LogicException('No handler found for command ' . get_class($command));
    }
}
