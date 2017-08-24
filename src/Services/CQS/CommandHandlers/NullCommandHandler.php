<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\CommandHandlers;

use Onyx\Services\CQS\CommandHandler;

class NullCommandHandler implements CommandHandler
{
    public function accept(\Onyx\Services\CQS\Command $command): bool
    {
        return true;
    }

    public function handle(\Onyx\Services\CQS\Command $command): void
    {
    }
}
