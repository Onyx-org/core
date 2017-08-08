<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\QueryHandlerProviders;

use Onyx\Services\CQS\Queries\NullQuery;
use Onyx\Services\CQS\QueryHandler;
use Onyx\Services\CQS\Query;
use Onyx\Services\CQS\QueryResult;
use PHPUnit\Framework\TestCase;
use Pimple\Container;

class PimpleTest extends TestCase
{
    private const
        NAMESPACE = 'CQS\Queries';

    /**
     * @expectedException \LogicException
     */
    public function testNoHandlerFoundException()
    {
        $provider = new Pimple(new Container(), self::NAMESPACE);
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

        $provider = new Pimple($container, self::NAMESPACE);
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

        $provider = new Pimple($container, self::NAMESPACE);
        $handler = $provider->findQueryHandlerFor(new NullQuery());

        $this->assertSame($expectedHandler, $handler);
    }
}
