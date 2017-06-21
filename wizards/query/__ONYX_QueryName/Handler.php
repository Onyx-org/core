<?php

declare(strict_types = 1);

namespace __ONYX_Namespace\Domain\Queries\__ONYX_QueryName;

use Onyx\Services\CQS\Query;
use Onyx\Services\CQS\QueryHandler;
use Onyx\Services\CQS\QueryResult;

class Handler implements QueryHandler
{
    public function accept(Query $query): bool
    {
        return $query instanceof __ONYX_QueryNameQuery;
    }

    public function handle(Query $query): QueryResult
    {
        $result = new Result();

        return $result;
    }
}
