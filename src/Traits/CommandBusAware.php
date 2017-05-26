<?php

declare(strict_types = 1);

namespace Onyx\Traits;

use Onyx\Services\CQS\CommandBus;

trait CommandBusAware
{
    private
        $commandBus;

    public function setCommandBus(CommandBus $commandBus): self
    {
        $this->commandBus= $commandBus;

        return $this;
    }
}
