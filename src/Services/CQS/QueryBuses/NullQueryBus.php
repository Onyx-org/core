<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\QueryBuses;

use Onyx\Services\CQS\QueryBus;
use Onyx\Services\CQS\QueryResult;
use Onyx\Services\CQS\Query;
use Onyx\Services\CQS\QueryResults\NullQueryResult;

class NullQueryBus implements QueryBus
{
    public function send(Query $query): QueryResult
    {
        return new NullQueryResult();
    }
}
