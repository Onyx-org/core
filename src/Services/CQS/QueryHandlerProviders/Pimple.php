<?php

declare(strict_types = 1);

namespace Onyx\Services\CQS\QueryHandlerProviders;

use Pimple\Container;
use Onyx\Services\CQS\Query;
use Onyx\Services\CQS\QueryHandler;
use Onyx\Services\CQS\QueryHandlerProvider;
use Onyx\Services\ServiceNameComputer;

class Pimple implements QueryHandlerProvider
{
    private const
        QUERY_HANDLER_PREFIX = 'query.handlers';

    private
        $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function findQueryHandlerFor(Query $query): QueryHandler
    {
        $serviceNameComputer = new ServiceNameComputer('Domain\Queries');

        $serviceNameWithoutPrefix = $serviceNameComputer->compute(get_class($query));
        $queryHandlerKeyInContainer = self::QUERY_HANDLER_PREFIX . '.' . $serviceNameWithoutPrefix;

        if(! $this->container->offsetExists($queryHandlerKeyInContainer))
        {
            throw new \LogicException(sprintf('the service "%s" does not exist in container', $queryHandlerKeyInContainer));
        }

        return $this->container[$queryHandlerKeyInContainer];
    }
}
