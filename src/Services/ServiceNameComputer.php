<?php

declare(strict_types = 1);

namespace Onyx\Services;

class ServiceNameComputer
{
    private
        $namespaceSeparator;

    public function __construct(string $namespaceSeparator)
    {
        $this->namespaceSeparator = $namespaceSeparator;
    }

    public function compute(string $namespace)
    {
        $queryRelativeNamespace = $this->computeQueryRelativeNamespace($namespace);

        $queryHandlerKey = strtolower($queryRelativeNamespace);
        $queryHandlerKey = ltrim($queryHandlerKey, '\\');

        return str_replace('\\', '_', $queryHandlerKey);
    }

    private function computeQueryRelativeNamespace(string $namespace)
    {
        $queryNamespaceParts = explode($this->namespaceSeparator, $namespace);

        if(is_array($queryNamespaceParts) && isset($queryNamespaceParts[1]))
        {
            return $queryNamespaceParts[1];
        }

        throw new \RuntimeException('Could not compute query relative namespace for query handler key');
    }
}
