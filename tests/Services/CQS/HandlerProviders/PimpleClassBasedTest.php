<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\HandlerProviders    ;

use PHPUnit\Framework\TestCase;
use Pimple\Container;
use Onyx\Services\CQS\QueryHandler;
use Onyx\Services\CQS\Query;
use Onyx\Services\CQS\QueryResult;
use Onyx\Services\CQS\Queries\NullQuery;
use Onyx\Services\CQS\Commands\NullCommand;
use Onyx\Services\CQS\CommandHandler;
use Onyx\Services\CQS\Command;

class PimpleClassBasedTest extends TestCase
{
    /**
     * @expectedException \LogicException
     */
    public function testNoQueryHandlerFoundException()
    {
        $provider = new PimpleClassBased(new Container());
        $provider->findQueryHandlerFor(new NullQuery());
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testWrongQueryHandlerTypeException()
    {
        $container = new Container([
            NullQuery::class => new \stdClass(),
        ]);

        $provider = new PimpleClassBased($container);
        $provider->findQueryHandlerFor(new NullQuery());
    }

    public function testFindQueryHandlerFor()
    {
        $expectedHandler = new class implements QueryHandler {
            public function accept(Query $query): bool {}
            public function handle(Query $query): QueryResult {}
        };

        $container = new Container([
            NullQuery::class => $expectedHandler,
        ]);

        $provider = new PimpleClassBased($container);
        $handler = $provider->findQueryHandlerFor(new NullQuery());

        $this->assertSame($expectedHandler, $handler);
    }
    /**
     * @expectedException \LogicException
     */
    public function testNoCommandHandlerFoundException()
    {
        $provider = new PimpleClassBased(new Container());
        $provider->findCommandHandlerFor(new NullCommand());
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testWrongCommandHandlerTypeException()
    {
        $container = new Container([
            NullCommand::class => new \stdClass(),
        ]);

        $provider = new PimpleClassBased($container);
        $provider->findCommandHandlerFor(new NullCommand());
    }

    public function testFindCommandHandlerFor()
    {
        $expectedHandler = new class implements CommandHandler {
            public function accept(Command $command): bool {}
            public function handle(Command $command): void {}
        };

        $container = new Container([
            NullCommand::class => $expectedHandler,
        ]);

        $provider = new PimpleClassBased($container);
        $handler = $provider->findCommandHandlerFor(new NullCommand());

        $this->assertSame($expectedHandler, $handler);
    }
}
