<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS;

interface QueryHandler
{
    public function accept(Query $query): bool;
    public function handle(Query $query): QueryResult;
}
