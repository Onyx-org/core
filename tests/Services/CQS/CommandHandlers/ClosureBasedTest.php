<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\CommandHandlers;

use PHPUnit\Framework\TestCase;
use Onyx\Services\CQS\Commands\NullCommand;

class ClosureBasedTest extends TestCase
{
    public function testSend()
    {
        $success = false;

        $handler = new ClosureBased(function ($cmd) use(& $success) {
            $success = true;
        });

        $this->assertFalse($success);

        $handler->handle(new NullCommand());

        $this->assertTrue($success);
    }
}
