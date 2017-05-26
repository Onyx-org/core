<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS;

interface CommandHandler
{
    public function accept(Command $command): bool;
    public function handle(Command $command): void;
}
