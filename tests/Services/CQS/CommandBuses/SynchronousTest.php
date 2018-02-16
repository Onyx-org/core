<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\CommandBuses;

use Onyx\Services\CQS\Commands\NullCommand;
use Onyx\Services\CQS\Command;
use Onyx\Services\CQS\CommandHandler;
use Onyx\Services\CQS\CommandHandlerProvider;
use PHPUnit\Framework\TestCase;

class SynchronousTest extends TestCase
{
    public function testSend()
    {
        $handler = $this->spyCommandHandler();
        $provider = $this->buildCommandHandlerProvider($handler);

        $synchronousBus = new Synchronous($provider);
        $synchronousBus->send(new NullCommand);

        $this->assertSame(1, $handler->callCount);
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

    private function spyCommandHandler()
    {
        return new Class implements CommandHandler {
            public $callCount = 0;

            public function handle(Command $command): void
            {
                $this->callCount++;
            }
        };
    }
}
