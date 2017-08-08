<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\QueryHandlerProviders;

use Onyx\Domain\Queries\NullQuery;
use Onyx\Services\CQS\QueryHandler;
use Onyx\Services\CQS\Query;
use Onyx\Services\CQS\QueryResult;
use PHPUnit\Framework\TestCase;
use Pimple\Container;

class PimpleTest extends TestCase
{
    /**
     * @expectedException \LogicException
     */
    public function testNoHandlerFoundException()
    {
        $provider = new Pimple(new Container());
        $provider->findQueryHandlerFor(new NullQuery());
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testBadHandlerTypeException()
    {
        $expectedHandler = new class {};

        $container = new Container([
            'query.handlers.nullquery' => $expectedHandler,
        ]);

        $provider = new Pimple($container);
        $provider->findQueryHandlerFor(new NullQuery());
    }

    public function testFindQueryHandlerFor()
    {
        $expectedHandler = new class implements QueryHandler {
            public function accept(Query $query): bool {}
            public function handle(Query $query): QueryResult {}
        };

        $container = new Container([
            'query.handlers.nullquery' => $expectedHandler,
        ]);

        $provider = new Pimple($container);
        $handler = $provider->findQueryHandlerFor(new NullQuery());

        $this->assertSame($expectedHandler, $handler);
    }
}
