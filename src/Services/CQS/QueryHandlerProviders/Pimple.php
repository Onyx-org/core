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
        QUERY_HANDLER_PREFIX = 'query.handlers',
        DEFAULT_NAMESPACE_SEPARATOR = 'Domain\Queries';

    private
        $container,
        $namespaceSeparator;

    public function __construct(Container $container, string $namespaceSeparator = self::DEFAULT_NAMESPACE_SEPARATOR)
    {
        $this->container = $container;
        $this->namespaceSeparator = $namespaceSeparator;
    }

    public function findQueryHandlerFor(Query $query): QueryHandler
    {
        $serviceNameComputer = new ServiceNameComputer($this->namespaceSeparator);

        $serviceNameWithoutPrefix = $serviceNameComputer->compute(get_class($query));
        $queryHandlerKeyInContainer = self::QUERY_HANDLER_PREFIX . '.' . $serviceNameWithoutPrefix;

        if(! $this->container->offsetExists($queryHandlerKeyInContainer))
        {
            throw new \LogicException(sprintf('the service "%s" does not exist in container', $queryHandlerKeyInContainer));
        }

        return $this->container[$queryHandlerKeyInContainer];
    }
}
