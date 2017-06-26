<?php

declare(strict_types = 1);

namespace Onyx\Services\Routes;

interface Retriever
{
    public function retrieveRoutes(): iterable;
}
