<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\CommandHandlerProviders;

use Pimple\Container;
use Onyx\Services\ServiceNameComputer;
use Onyx\Services\CQS\CommandHandlerProvider;
use Onyx\Services\CQS\CommandHandler;
use Onyx\Services\CQS\Command;

class Pimple implements CommandHandlerProvider
{
    private const
        COMMAND_HANDLER_PREFIX = 'command.handlers',
        DEFAULT_NAMESPACE_SEPARATOR = 'Domain\Commands';

    private
        $container,
        $namespaceSeparator;

    public function __construct(Container $container, string $namespaceSeparator = self::DEFAULT_NAMESPACE_SEPARATOR)
    {
        $this->container = $container;
        $this->namespaceSeparator = $namespaceSeparator;
    }

    public function findCommandHandlerFor(Command $command): CommandHandler
    {
        $serviceNameComputer = new ServiceNameComputer($this->namespaceSeparator);

        $serviceNameWithoutPrefix = $serviceNameComputer->compute(get_class($command));
        $commandHandlerKeyInContainer = self::COMMAND_HANDLER_PREFIX . '.' . $serviceNameWithoutPrefix;

        if(! $this->container->offsetExists($commandHandlerKeyInContainer))
        {
            throw new \LogicException(sprintf('the service "%s" does not exist in container', $commandHandlerKeyInContainer));
        }

        $commandHandler = $this->container[$commandHandlerKeyInContainer];

        if(! $commandHandler instanceof CommandHandler)
        {
            throw new \UnexpectedValueException(sprintf('The command handler "%s" does not implement CommandHandler', get_class($commandHandler)));
        }

        return $commandHandler;
    }
}
