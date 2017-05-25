<?php

declare(strict_types = 1);

namespace Onyx\Traits;

use Onyx\Services\CQS\QueryBus;

trait QueryBusAware
{
    private
        $queryBus;

    public function setQueryBus(QueryBus $queryBus): self
    {
        $this->queryBus = $queryBus;

        return $this;
    }
}
