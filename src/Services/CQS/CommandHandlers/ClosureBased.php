<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\CommandHandlers;

use Onyx\Services\CQS\CommandHandler;
use Onyx\Services\CQS\Command;

class ClosureBased implements CommandHandler
{
    private
        $closure;

    public function __construct(\Closure $closure)
    {
        $this->closure = $closure;
    }

    public function accept(Command $command): bool
    {
        return true;
    }

    public function handle(Command $command): void
    {
        $closure = $this->closure;
        $closure($command);
    }
}
