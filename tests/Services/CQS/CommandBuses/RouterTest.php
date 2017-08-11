<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\CommandBuses;

use PHPUnit\Framework\TestCase;
use Onyx\Services\CQS\CommandHandlerProvider;
use Onyx\Services\CQS\Commands\NullCommand;
use Onyx\Services\CQS\AsynchronousCommand;
use Onyx\Services\CQS\Command;
use Onyx\Services\CQS\CommandHandlers\ClosureBased;
use Onyx\Services\CQS\CommandHandler;

class RouterTest extends TestCase
{
    private function asynchronousBus(CommandHandlerProvider $provider): AsynchronousCommandBus
    {
        return new class($provider) extends Synchronous implements AsynchronousCommandBus {};
    }

    private function handlerProvider(\Closure $closure): CommandHandlerProvider
    {
        $handler = new ClosureBased($closure);

        return new class($handler) implements CommandHandlerProvider
        {
            private $handler;
            public function __construct(CommandHandler $handler) { $this->handler = $handler; }
            public function findCommandHandlerFor(Command $command): CommandHandler { return $this->handler; }
        };
    }

    /**
     * @dataProvider providerTestSend
     */
    public function testSend(Command $cmd, array $expected)
    {
        $success = [
            'sync' => false,
            'async' => false,
        ];

        $syncProvider = $this->handlerProvider(function ($cmd) use(& $success) {
            $success['sync'] = true;
        });

        $asyncProvider = $this->handlerProvider(function ($cmd) use(& $success) {
            $success['async'] = true;
        });

        $router = new Router(new Synchronous($syncProvider), $this->asynchronousBus($asyncProvider));
        $router->send($cmd);

        $this->assertEquals($expected, $success);
    }

    public function providerTestSend()
    {
        $expected = function($sync, $async) { return ['sync' => $sync, 'async' => $async]; };

        return [
            [new NullCommand(), $expected(true, false)],
            [new class() extends NullCommand implements AsynchronousCommand {}, $expected(false, true)],
        ];
    }
}
