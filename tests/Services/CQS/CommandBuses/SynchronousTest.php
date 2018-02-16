<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\CommandBuses;

use Onyx\Services\CQS\Commands\NullCommand;
use Onyx\Services\CQS\Command;
use Onyx\Services\CQS\CommandHandler;
use Onyx\Services\CQS\CommandHandlerProvider;
use PHPUnit\Framework\TestCase;
use Onyx\Services\CQS\CommandHandlers\ClosureBased;

class SynchronousTest extends TestCase
{
    public function testSend()
    {
        $called = false;

        $provider = $this->buildCommandHandlerProvider(
            new ClosureBased(function(Command $command) use(& $called): void {
                $called = true;
            })
        );

        $synchronousBus = new Synchronous($provider);
        $synchronousBus->send(new NullCommand);

        $this->assertTrue($called);
    }

    private function buildCommandHandlerProvider(CommandHandler $commandHandler)
    {
        return new Class($commandHandler) implements CommandHandlerProvider {
            private
                $commandHandler;

            public function __construct($commandHandler)
            {
                $this->commandHandler = $commandHandler;
            }

            public function findCommandHandlerFor(Command $command): CommandHandler
            {
                return $this->commandHandler;
            }
        };
    }
}
