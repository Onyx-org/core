<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\QueryBuses;

use Onyx\Services\CQS\QueryBus;
use Onyx\Services\CQS\QueryResult;
use Onyx\Services\CQS\Query;
use Onyx\Services\CQS\QueryHandlerProvider;

class Synchronous implements QueryBus
{
    private
        $queryHandlerProvider;

    public function __construct(QueryHandlerProvider $queryHandlerProvider)
    {
        $this->queryHandlerProvider = $queryHandlerProvider;
    }

    public function send(Query $query): QueryResult
    {
        $handler = $this->queryHandlerProvider->findQueryHandlerFor($query);

        if($handler->accept($query))
        {
            return $handler->handle($query);
        }

        throw new \LogicException('No handler found for query ' . get_class($query));
    }
}
