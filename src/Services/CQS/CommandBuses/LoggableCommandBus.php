<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\CommandBuses;

use Onyx\Services\CQS\CommandBus;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Onyx\Services\CQS\Loggable;
use Psr\Log\LogLevel;
use Puzzle\Pieces\ConvertibleToString;
use Puzzle\Pieces\StringManipulation;

class LoggableCommandBus implements CommandBus
{
    use LoggerAwareTrait;
    use StringManipulation;

    private
        $bus,
        $currentUser,
        $level;

    public function __construct(CommandBus $bus, $currentUser = null)
    {
        $this->bus = $bus;

        $this->level = LogLevel::INFO;
        $this->currentUser = $this->isConvertibleToString($currentUser) ? $currentUser : null;
        $this->logger = new NullLogger();
    }

    public function changeLogLevel(string $level): void
    {
        $this->level = $level;
    }

    public function send(Command $command): void
    {
        $this->log($command);
        $this->bus->send($command);
    }

    private function log(Command $command): void
    {
        $message = '';
        $context = [];

        if($command instanceof Loggable)
        {
            $message = $command->logMessage();
            $context = $command->logContext();
        }
        else
        {
            list($message, $context) = $this->buildLogEntry($command);
        }

        $context['_user'] = (string) $this->currentUser;

        $this->logger->log($this->level, $message, $context);
    }

    protected function buildLogEntry(Command $command): array
    {
        $message = 'Command sent';

        $context = [
            'command' => get_class($command),
        ];

        return [$message, $context];
    }
}
