<?php

namespace Onyx\Logger\Monolog;

use Monolog\Handler\HandlerInterface;
use Onyx\Logger\MonologHandlerCollector;
use Puzzle\Pieces\OutputInterfaceAware;
use Symfony\Component\Console\Output\OutputInterface;
use Monolog\Handler\PsrHandler;
use Symfony\Component\Console\Logger\ConsoleLogger;

class HandlerCollector implements MonologHandlerCollector, OutputInterfaceAware
{
    private
        $handlers;

    public function __construct()
    {
        $this->handlers = array();
    }

    public function pushHandler(HandlerInterface $handler, bool $prioritary = true): void
    {
        if($prioritary === false)
        {
            array_unshift($this->handlers, $handler);

            return;
        }

        $this->handlers[] = $handler;
    }

    public function getHandlers(): iterable
    {
        return $this->handlers;
    }

    public function register(OutputInterface $output)
    {
        $this->pushHandler(
            new PsrHandler(new ConsoleLogger($output))
        );
    }
}
