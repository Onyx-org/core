<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\QueryBuses;

use Onyx\Services\CQS\Query;
use Onyx\Services\CQS\QueryBus;
use Onyx\Services\CQS\QueryHandler;
use Onyx\Services\CQS\QueryHandlers\NullQueryHandler;
use Onyx\Services\CQS\QueryResult;

class InMemory implements QueryBus
{
    private
        $uniqueHandler,
        $sentQueries;

    public function __construct(?QueryHandler $uniqueHandler = null)
    {
        $this->setUniqueQueryHandler($uniqueHandler);
        $this->sentQueries = [];
    }

    public function send(Query $query): QueryResult
    {
        $this->sentQueries[] = $query;

        return $this->uniqueHandler->handle($query);
    }

    public function getSentQueries(): iterable
    {
        return $this->sentQueries;
    }

    public function getLastSentQuery(): ?Query
    {
        $lastQuery = end($this->sentQueries);
        if ($lastQuery === false)
        {
            $lastQuery = null;
        }

        return $lastQuery;
    }

    private function setUniqueQueryHandler(?QueryHandler $queryHandler): void
    {
        $handler = new NullQueryHandler();

        if($queryHandler instanceof QueryHandler)
        {
            $handler = $queryHandler;
        }

        $this->uniqueHandler = $handler;
    }
}
