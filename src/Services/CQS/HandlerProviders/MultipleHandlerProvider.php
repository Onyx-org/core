<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\HandlerProviders;

use Onyx\Services\CQS\QueryHandlerProvider;
use Onyx\Services\CQS\CommandHandlerProvider;
use Onyx\Services\CQS\Query;
use Onyx\Services\CQS\QueryHandler;
use Onyx\Services\CQS\Command;
use Onyx\Services\CQS\CommandHandler;
use Onyx\Services\CQS\HandlerProviders\Exceptions\NoValidHandlerFound;

class MultipleHandlerProvider implements QueryHandlerProvider, CommandHandlerProvider
{
    private
        $queryHandlerProviders,
        $commandHandlerProviders;

    public function __construct(iterable $providers)
    {
        $this->queryHandlerProviders = [];
        $this->commandHandlerProviders = [];

        foreach($providers as $provider)
        {
            if($provider instanceof QueryHandlerProvider)
            {
                $this->queryHandlerProviders[] = $provider;
            }

            if($provider instanceof CommandHandlerProvider)
            {
                $this->commandHandlerProviders[] = $provider;
            }
        }
    }

    public function findQueryHandlerFor(Query $query): QueryHandler
    {
        foreach($this->queryHandlerProviders as $provider)
        {
            try
            {
                $handler = $provider->findQueryHandlerFor($query);
                return $handler;
            }
            catch(NoValidHandlerFound $e)
            {
                continue;
            }
        }

        throw new NoValidHandlerFound(sprintf('Handler not found for query %s', get_class($query)));
    }

    public function findCommandHandlerFor(Command $command): CommandHandler
    {
        foreach($this->commandHandlerProviders as $provider)
        {
            try
            {
                $handler = $provider->findCommandHandlerFor($command);
                return $handler;
            }
            catch(NoValidHandlerFound $e)
            {
                continue;
            }
        }

        throw new NoValidHandlerFound(sprintf('Handler not found for command %s', get_class($command)));
    }
}
