<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS;

interface CommandHandlerProvider
{
    public function findCommandHandlerFor(Command $command): CommandHandler;
}
