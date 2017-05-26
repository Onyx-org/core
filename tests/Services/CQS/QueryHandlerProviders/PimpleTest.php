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
        $container = new Container();

        $pimpleQueryHandlerProvider = new Pimple($container);

        $query = new NullQuery();

        $pimpleQueryHandlerProvider->findQueryHandlerFor($query);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testBadHandlerTypeException()
    {
        $container = new Container();

        $expectedQueryHandler = new class {};

        $container['query.handlers.nullquery'] = function() use($expectedQueryHandler){
            return $expectedQueryHandler;
        };

        $pimpleQueryHandlerProvider = new Pimple($container);

        $queryHandler = $pimpleQueryHandlerProvider->findQueryHandlerFor(new NullQuery());
    }

    public function testFindQueryHandlerFor()
    {
        $container = new Container();

        $expectedQueryHandler = new class implements QueryHandler {
            public function accept(Query $query): bool
            {}
            public function handle(Query $query): QueryResult
            {}
        };

        $container['query.handlers.nullquery'] = function() use($expectedQueryHandler){
            return $expectedQueryHandler;
        };

        $pimpleQueryHandlerProvider = new Pimple($container);

        $queryHandler = $pimpleQueryHandlerProvider->findQueryHandlerFor(new NullQuery());

        $this->assertSame($expectedQueryHandler, $queryHandler);
    }
}
