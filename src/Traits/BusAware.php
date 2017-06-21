<?php

declare(strict_types = 1);

namespace Onyx\Traits;

use Onyx\Services\CQS\CommandBus;
use Onyx\Services\CQS\QueryBus;

trait BusAware
{
    use
        QueryBusAware,
        CommandBusAware;

    public function setBuses(QueryBus $queryBus, CommandBus $commandBus): self
    {
        $this->setQueryBus($queryBus);
        $this->setCommandBus($commandBus);

        return $this;
    }
}
