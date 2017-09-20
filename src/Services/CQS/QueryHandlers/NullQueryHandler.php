<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\QueryHandlers;

use Onyx\Services\CQS\QueryHandler;
use Onyx\Services\CQS\Query;
use Onyx\Services\CQS\QueryResult;
use Onyx\Services\CQS\QueryResults\NullQueryResult;

class NullQueryHandler implements QueryHandler
{
    public function accept(Query $query): bool
    {
        return true;
    }

    public function handle(Query $query): QueryResult
    {
        return new NullQueryResult();
    }
}
