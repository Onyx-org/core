<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\CommandBuses;

use Onyx\Services\CQS\Command;
use Onyx\Services\CQS\CommandBus;

class InMemory implements CommandBus
{
    private
        $sentCommands;

    public function __construct()
    {
        $this->sentCommands = [];
    }

    public function send(Command $command): void
    {
        $this->sentCommands[] = $command;
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
}
