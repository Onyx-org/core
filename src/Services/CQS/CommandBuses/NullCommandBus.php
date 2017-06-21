<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\CommandBuses;

use Onyx\Services\CQS\CommandBus;
use Onyx\Services\CQS\Command;

class NullCommandBus implements CommandBus
{
    public function send(Command $command): void
    {
    }
}
