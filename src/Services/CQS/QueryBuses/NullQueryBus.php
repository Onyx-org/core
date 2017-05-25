<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\QueryBuses;

use Onyx\Services\CQS\QueryBus;
use Onyx\Services\CQS\QueryResult;
use Onyx\Services\CQS\Query;

class NullQueryBus implements QueryBus
{
    public function send(Query $query): QueryResult
    {
        return new class implements QueryResult
        {
        };
    }
}
