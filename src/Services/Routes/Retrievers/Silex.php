<?php

declare(strict_types = 1);

namespace Onyx\Services\Routes\Retrievers;

use Onyx\Services\Routes\Retriever;
use Pimple\Container;

class Silex implements Retriever
{
    private
        $container;
    
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    
    public function retrieveRoutes(): iterable
    {
        $this->container->flush();
    
        return $this->container['routes'];
    }
}
