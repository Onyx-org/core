<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\HandlerProviders;

use Onyx\Services\CQS\QueryHandlerProvider;
use Pimple\Container;
use Onyx\Services\CQS\QueryHandler;
use Onyx\Services\CQS\Query;
use Onyx\Services\CQS\CommandHandlerProvider;
use Onyx\Services\CQS\Command;
use Onyx\Services\CQS\CommandHandler;
use Onyx\Services\CQS\HandlerProviders\Exceptions\NoValidHandlerFound;

class PimpleClassBased implements QueryHandlerProvider, CommandHandlerProvider
{
    private
        $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function findQueryHandlerFor(Query $query): QueryHandler
    {
        $handler = $this->findHandler($query);

        if(! $handler instanceof QueryHandler)
        {
            throw new \UnexpectedValueException(sprintf('The query handler "%s" does not implement QueryHandler', get_class($handler)));
        }

        return $handler;
    }

    public function findCommandHandlerFor(Command $command): CommandHandler
    {
        $handler = $this->findHandler($command);

        if(! $handler instanceof CommandHandler)
        {
            throw new \UnexpectedValueException(sprintf('The command handler "%s" does not implement CommandHandler', get_class($handler)));
        }

        return $handler;
    }

    private function findHandler($for)
    {
        $key = get_class($for);

        if(! $this->container->offsetExists($key))
        {
            throw new NoValidHandlerFound(sprintf('The service "%s" does not exist in container', $key));
        }

        return $this->container[$key];
    }
}
