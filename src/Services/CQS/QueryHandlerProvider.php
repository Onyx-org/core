<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS;

interface QueryHandlerProvider
{
    public function findQueryHandlerFor(Query $query): QueryHandler;
}
