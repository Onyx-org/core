<?php

namespace Onyx\Logger;

use Monolog\Handler\HandlerInterface;

interface MonologHandlerCollector
{
    public function pushHandler(HandlerInterface $handler, bool $prioritary = true): void;

    public function getHandlers(): iterable;
}
