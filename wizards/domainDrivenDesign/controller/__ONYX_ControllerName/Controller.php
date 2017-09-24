<?php

declare(strict_types = 1);

namespace __ONYX_Namespace\__ONYX_BoundedContext\Infrastructure\Controllers\__ONYX_BackOrFront\__ONYX_ControllerName;

use Onyx\Traits;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

class Controller
{
    use
        Traits\RequestAware,
        Traits\TwigAware,
        LoggerAwareTrait;

    public function __construct()
    {
        $this->logger = new NullLogger();
    }

    public function homeAction(): Response
    {
        return $this->render('__ONYX_BoundedContext_LC/__ONYX_BackOrFront_LC/home.twig');
    }
}
