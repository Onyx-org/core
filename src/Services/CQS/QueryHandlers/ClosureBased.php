<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\QueryHandlers;

use Onyx\Services\CQS\QueryHandler;
use Onyx\Services\CQS\Query;
use Onyx\Services\CQS\QueryResult;

class ClosureBased implements QueryHandler
{
    private
        $closure;

    public function __construct(\Closure $closure)
    {
        $this->closure = $closure;
    }

    public function accept(Query $query): bool
    {
        return true;
    }

    public function handle(Query $query): QueryResult
    {
        $closure = $this->closure;

        return $closure($query);
    }
}
