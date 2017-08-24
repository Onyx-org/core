<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\CommandBuses;

use Onyx\Services\CQS\Command;
use Onyx\Services\CQS\CommandBus;
use Onyx\Services\CQS\CommandHandler;
use Onyx\Services\CQS\CommandHandlers\NullCommandHandler;

class InMemory implements CommandBus
{
    private
        $uniqueHandler,
        $sentCommands;

    public function __construct(?CommandHandler $uniqueHandler = null)
    {
        $this->setUniqueCommandHandler($uniqueHandler);
        $this->sentCommands = [];
    }

    public function send(Command $command): void
    {
        $this->sentCommands[] = $command;

        $this->uniqueHandler->handle($command);
    }

    public function getSentCommands(): iterable
    {
        return $this->sentCommands;
    }

    public function getLastSentCommand(): ?Command
    {
        $lastCommand = end($this->sentCommands);
        if ($lastCommand === false)
        {
            $lastCommand = null;
        }

        return $lastCommand;
    }

    private function setUniqueCommandHandler(?CommandHandler $commandHandler): void
    {
        $handler = new NullCommandHandler();

        if($commandHandler instanceof CommandHandler)
        {
            $handler = $commandHandler;
        }

        $this->uniqueHandler = $handler;
    }
}
