<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\CommandBuses;

use Onyx\Services\CQS\CommandBus;
use Onyx\Services\CQS\Command;
use Onyx\Services\CQS\AsynchronousCommand;

class Router implements CommandBus
{
    private
        $synchronous,
        $asynchronous;

    public function __construct(CommandBus $synchronousBus, AsynchronousCommandBus $asynchronousBus)
    {
        $this->synchronous = $synchronousBus;
        $this->asynchronous = $asynchronousBus;
    }

    public function send(Command $command): void
    {
        if($command instanceof AsynchronousCommand)
        {
            $this->asynchronous->send($command);

            return;
        }

        $this->synchronous->send($command);
    }
}
