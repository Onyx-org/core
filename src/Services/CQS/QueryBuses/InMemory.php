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

    public function send(Query $Query): QueryResult
    {
        $this->sentQueries[] = $Query;

        return $this->uniqueHandler->handle($Query);
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

    private function setUniqueQueryHandler(?QueryHandler $QueryHandler): void
    {
        $handler = new NullQueryHandler();

        if($QueryHandler instanceof QueryHandler)
        {
            $handler = $QueryHandler;
        }

        $this->uniqueHandler = $handler;
    }
}
