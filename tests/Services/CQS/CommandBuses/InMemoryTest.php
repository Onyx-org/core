<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\CommandBuses;

use Onyx\Services\CQS\Command;
use Onyx\Services\CQS\CommandBus;
use Onyx\Services\CQS\CommandHandlers\ClosureBased;
use PHPUnit\Framework\TestCase;

class InMemoryTest extends TestCase
{
    private
        $command1,
        $command2;

    protected function setUp()
    {
        $this->command1 = new class implements Command{};
        $this->command2 = new class implements Command{};
    }

    public function testSend()
    {
        $bus = new InMemory();
        $this->assertCount(0, $bus->getSentCommands());
        $this->assertSame(null, $bus->getLastSentCommand());

        $bus->send($this->command1);
        $this->assertCount(1, $bus->getSentCommands());
        $this->assertSame($this->command1, $bus->getLastSentCommand());

        $bus->send($this->command1);
        $this->assertCount(2, $bus->getSentCommands());
        $this->assertSame($this->command1, $bus->getLastSentCommand());

        $bus->send($this->command2);
        $this->assertCount(3, $bus->getSentCommands());
        $this->assertSame($this->command2, $bus->getLastSentCommand());
    }

    public function testUniqueHandler()
    {
        $command = new class implements Command{ public $theCommandHasBeenHandled; };

        $bus = new InMemory(new ClosureBased(function(Command $command) {
            $command->theCommandHasBeenHandled = true;
        }));
        $bus->send($command);

        $this->assertTrue($command->theCommandHasBeenHandled);
    }
}
