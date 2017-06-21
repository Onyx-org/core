<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS;

interface CommandBus
{
    public function send(Command $command): void;
}
