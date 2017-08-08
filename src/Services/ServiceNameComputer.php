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
        $relativeNamespace = $this->computeRelativeNamespace($namespace);

        $handlerKey = strtolower($relativeNamespace);
        $handlerKey = ltrim($handlerKey, '\\');

        return str_replace('\\', '_', $handlerKey);
    }

    private function computeRelativeNamespace(string $namespace)
    {
        $queryNamespaceParts = explode($this->namespaceSeparator, $namespace);

        if(is_array($queryNamespaceParts) && isset($queryNamespaceParts[1]))
        {
            return $queryNamespaceParts[1];
        }

        throw new \RuntimeException("Could not compute relative namespace for handler key ($namespace)");
    }
}
