<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\CommandHandlerProviders;

use Onyx\Services\CQS\Commands\NullCommand;
use Onyx\Services\CQS\CommandHandler;
use Onyx\Services\CQS\Command;
use PHPUnit\Framework\TestCase;
use Pimple\Container;

class PimpleTest extends TestCase
{
    private const
        NAMESPACE = 'CQS\Commands';

    /**
     * @expectedException \LogicException
     */
    public function testNoHandlerFoundException()
    {
        $provider = new Pimple(new Container(), self::NAMESPACE);
        $provider->findCommandHandlerFor(new NullCommand());
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testBadHandlerTypeException()
    {
        $expectedHandler = new class {};

        $container = new Container([
            'command.handlers.nullcommand' => $expectedHandler,
        ]);

        $provider = new Pimple($container, self::NAMESPACE);
        $provider->findCommandHandlerFor(new NullCommand());
    }

    /**
     * @dataProvider providerTestFindCommandHandlerFor
     */
    public function testFindCommandHandlerFor(Command $command, string $handlerServiceKey, ?string $namespaceSeparator)
    {
        $expectedHandler = new class implements CommandHandler {
            public function accept(Command $command): bool {}
            public function handle(Command $command): void {}
        };

        $container = new Container([
            $handlerServiceKey => $expectedHandler,
        ]);

        $provider = new Pimple($container);
        if(! empty($namespaceSeparator))
        {
            $provider = new Pimple($container, $namespaceSeparator);
        }

        $handler = $provider->findCommandHandlerFor($command);

        $this->assertSame($expectedHandler, $handler);
    }

    public function providerTestFindCommandHandlerFor()
    {
        return [
            'Default namespace separator' => [
                'command' => new NullCommand(),
                'Handler service key in container' => 'command.handlers.nullcommand',
                'namespace separator' => self::NAMESPACE,
            ],
            'Custom namespace separator' => [
                'command' => new NonDefaultNamespaceCommand(),
                'Handler service key in container' => 'command.handlers.nondefaultnamespacecommand',
                'namespace separator' => 'CommandHandlerProviders',
            ],
        ];
    }
}

class NonDefaultNamespaceCommand implements Command
{
}
