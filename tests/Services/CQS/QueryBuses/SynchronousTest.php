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
use Onyx\Services\CQS\QueryHandlers\ClosureBased;

class SynchronousTest extends TestCase
{
    public function testSend()
    {
        $called = false;

        $provider = $this->buildQueryHandlerProvider(
            new ClosureBased(function(Query $query) use(& $called): QueryResult {
                $called = true;
                return new NullQueryResult();
            })
        );

        $synchronousBus = new Synchronous($provider);
        $synchronousBus->send(new NullQuery());

        $this->assertTrue($called);
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
}
