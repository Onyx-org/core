<?php

declare(strict_types = 1);

namespace Onyx\Console\Commands\Routes;

interface Retriever
{
    public function retrieveRoutes(): iterable;
}
