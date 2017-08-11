<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\CommandHandlers;

use PHPUnit\Framework\TestCase;
use Onyx\Services\CQS\Commands\NullCommand;
use Onyx\Services\CQS\Command;

class ClosureBasedTest extends TestCase
{
    /**
     * @dataProvider providerTestAccept
     */
    public function testAccept(Command $cmd)
    {
        $handler = new ClosureBased(function ($cmd) {});

        $this->assertTrue($handler->accept($cmd));
    }

    public function providerTestAccept()
    {
        return [
            [new NullCommand()],
            [new class implements Command {}],
        ];
    }

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
