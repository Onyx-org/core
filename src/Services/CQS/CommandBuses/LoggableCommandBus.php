<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\CommandBuses;

use Onyx\Services\CQS\CommandBus;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Onyx\Services\CQS\Loggable;
use Psr\Log\LogLevel;
use Puzzle\Pieces\StringManipulation;
use Onyx\Services\CQS\Command;

class LoggableCommandBus implements CommandBus
{
    use LoggerAwareTrait;
    use StringManipulation;

    protected
        $bus,
        $blacklist,
        $currentUser,
        $level;

    public function __construct(CommandBus $bus, $currentUser = null)
    {
        $this->bus = $bus;

        $this->blacklist = [];
        $this->level = LogLevel::INFO;
        $this->currentUser = $this->isConvertibleToString($currentUser) ? $currentUser : null;
        $this->logger = new NullLogger();
    }

    public function changeLogLevel(string $level): void
    {
        $this->level = $level;
    }

    public function excludeClassFromLog(array $classnames): void
    {
        $this->blacklist += $classnames;
    }

    public function send(Command $command): void
    {
        if($this->mustLog($command))
        {
            $this->log($command);
        }

        $this->bus->send($command);
    }

    protected function mustLog($command): bool
    {
        return ! in_array(get_class($command), $this->blacklist);
    }

    protected function log(Command $command): void
    {
        $message = 'Command sent';
        $context = [
            '_command' => $this->commandName($command),
        ];

        if($command instanceof Loggable)
        {
            $context += $command->logContext();
        }

        if($this->currentUser !== null)
        {
            $context['_user'] = (string) $this->currentUser;
        }

        $this->logger->log($this->level, $message, $context);
    }

    protected function commandName(Command $command): string
    {
        return get_class($command);
    }
}
