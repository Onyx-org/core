<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\QueryBuses;

use Onyx\Domain\Queries\NullQuery;
use Onyx\Services\CQS\Query;
use Onyx\Services\CQS\QueryResult;
use Onyx\Services\CQS\QueryHandler;
use Onyx\Services\CQS\QueryHandlerProvider;
use PHPUnit\Framework\TestCase;

class SynchronousTest extends TestCase
{
    /**
     * @expectedException \LogicException
     */
    public function testSendException()
    {
        $queryHandler = $this->buildRefusedQueryQueryHandler();
        $queryHandlerProvider = $this->buildQueryHandlerProvider($queryHandler);

        $synchronousBus = new Synchronous($queryHandlerProvider);

        $synchronousBus->send(new NullQuery());
    }

    public function testSend()
    {
        $queryHandler = $this->buildAcceptedQueryQueryHandler();
        $queryHandlerProvider = $this->buildQueryHandlerProvider($queryHandler);

        $synchronousBus = new Synchronous($queryHandlerProvider);

        $synchronousBus->send(new NullQuery());

        $this->assertSame(1, $queryHandler->callCount);
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

    private function buildRefusedQueryQueryHandler()
    {
        return new Class implements QueryHandler {
            public function accept(Query $query): bool
            {
                return false;
            }
            public function handle(Query $query): QueryResult
            {
            }
        };
    }

    private function buildAcceptedQueryQueryHandler()
    {
        return new Class implements QueryHandler {
            public $callCount = 0;

            public function accept(Query $query): bool
            {
                return true;
            }
            public function handle(Query $query): QueryResult
            {
                $this->callCount++;

                return new Class implements QueryResult {};
            }
        };
    }
}
