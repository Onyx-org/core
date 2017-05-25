<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS;

interface QueryBus
{
    public function send(Query $query): QueryResult;
}
