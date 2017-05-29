<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\CommandBuses;

use Onyx\Services\CQS\Command;
use Onyx\Services\CQS\CommandHandler;
use Onyx\Services\CQS\CommandHandlerProvider;
use PHPUnit\Framework\TestCase;

class SynchronousTest extends TestCase
{
    /**
     * @expectedException \LogicException
     */
    public function testSendException()
    {
        $commandHandler = $this->buildRefusedCommandCommandHandler();
        $commandHandlerProvider = $this->buildCommandHandlerProvider($commandHandler);

        $synchronousBus = new Synchronous($commandHandlerProvider);

        $synchronousBus->send($this->buildCommand());
    }

    public function testSend()
    {
        $commandHandler = $this->buildAcceptedCommandCommandHandler();
        $commandHandlerProvider = $this->buildCommandHandlerProvider($commandHandler);

        $synchronousBus = new Synchronous($commandHandlerProvider);

        $synchronousBus->send($this->buildCommand());

        $this->assertSame(1, $commandHandler->callCount);
    }

    private function buildCommand()
    {
        return new Class implements Command {};
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

    private function buildRefusedCommandCommandHandler()
    {
        return new Class implements CommandHandler {
            public function accept(Command $command): bool
            {
                return false;
            }
            public function handle(Command $command): void
            {
            }
        };
    }

    private function buildAcceptedCommandCommandHandler()
    {
        return new Class implements CommandHandler {
            public $callCount = 0;

            public function accept(Command $command): bool
            {
                return true;
            }
            public function handle(Command $command): void
            {
                $this->callCount++;
            }
        };
    }
}
