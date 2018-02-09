<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS;

interface Loggable
{
    public function logMessage(): string;
    public function logContext(): array;
}
