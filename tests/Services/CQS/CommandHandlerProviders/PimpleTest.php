<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\CommandHandlerProviders;

use Onyx\Domain\Commands\NullCommand;
use Onyx\Services\CQS\CommandHandler;
use Onyx\Services\CQS\Command;
use PHPUnit\Framework\TestCase;
use Pimple\Container;

class PimpleTest extends TestCase
{
    /**
     * @expectedException \LogicException
     */
    public function testNoHandlerFoundException()
    {
        $container = new Container();

        $pimpleCommandHandlerProvider = new Pimple($container);

        $command = new NullCommand();

        $pimpleCommandHandlerProvider->findCommandHandlerFor($command);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testBadHandlerTypeException()
    {
        $container = new Container();

        $expectedCommandHandler = new class {};

        $container['command.handlers.nullcommand'] = function() use($expectedCommandHandler){
            return $expectedCommandHandler;
        };

        $pimpleCommandHandlerProvider = new Pimple($container);

        $commandHandler = $pimpleCommandHandlerProvider->findCommandHandlerFor(new NullCommand());
    }

    public function testFindCommandHandlerFor()
    {
        $container = new Container();

        $expectedCommandHandler = new class implements CommandHandler {
            public function accept(Command $command): bool
            {}
            public function handle(Command $command): void
            {}
        };

        $container['command.handlers.nullcommand'] = function() use($expectedCommandHandler){
            return $expectedCommandHandler;
        };

        $pimpleCommandHandlerProvider = new Pimple($container);

        $commandHandler = $pimpleCommandHandlerProvider->findCommandHandlerFor(new NullCommand());

        $this->assertSame($expectedCommandHandler, $commandHandler);
    }
}
