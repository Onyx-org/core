<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\QueryBuses;

use Onyx\Services\CQS\Queries\NullQuery;
use Onyx\Services\CQS\Query;
use Onyx\Services\CQS\QueryResult;
use Onyx\Services\CQS\QueryHandler;
use Onyx\Services\CQS\QueryHandlerProvider;
use PHPUnit\Framework\TestCase;
use Onyx\Services\CQS\QueryResults\NullQueryResult;

class SynchronousTest extends TestCase
{
    public function testSend()
    {
        $handler = $this->spyQueryHandler();
        $provider = $this->buildQueryHandlerProvider($handler);

        $synchronousBus = new Synchronous($provider);
        $synchronousBus->send(new NullQuery());

        $this->assertSame(1, $handler->callCount);
    }

    private function buildQueryHandlerProvider(QueryHandler $queryHandler)
    {
        return new Class($queryHandler) implements QueryHandlerProvider {
            private
                $queryHandler;

            public function __construct($queryHandler)
            {
                $this->queryHandler = $queryHandler;
            }

            public function findQueryHandlerFor(Query $query): QueryHandler
            {
                return $this->queryHandler;
            }
        };
    }

    private function spyQueryHandler()
    {
        return new Class implements QueryHandler {
            public $callCount = 0;

            public function handle(Query $query): QueryResult
            {
                $this->callCount++;

                return new NullQueryResult();
            }
        };
    }
}
