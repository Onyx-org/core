<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\CommandHandlers;

use Onyx\Services\CQS\CommandHandler;
use Onyx\Services\CQS\Command;

class NullCommandHandler implements CommandHandler
{
    public function handle(Command $command): void
    {
    }
}
