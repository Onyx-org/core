<?php

declare(strict_types = 1);

namespace Onyx;

use Puzzle\Configuration;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\RoutingServiceProvider;
use Onyx\Traits;

abstract class Application extends \Silex\Application
{
    use
        Traits\PathManipulation;

    public function __construct(Configuration $configuration, string $rootDir)
    {
        parent::__construct();

        $this['configuration'] = $configuration;
        $this->enableDebug();
        $this->initializePaths($rootDir);

        $this->register(new ServiceControllerServiceProvider());
        $this->registerProviders();
        $this->initializeUrlGeneratorProvider();

        $this->initializeServices();

        $this->mountControllerProviders();
    }

    private function initializePaths(string $rootDir): void
    {
        $this['root.path'] = $this->enforceEndingSlash($rootDir);
        $this['documentRoot.path'] = $this['root.path'] . 'www' . DIRECTORY_SEPARATOR;
        $this['var.path'] = $this['root.path'] . $this->removeWrappingSlashes($this['configuration']->readRequired('app/var.path')) . DIRECTORY_SEPARATOR;
    }

    private function enableDebug(): void
    {
        $this['debug'] = $this['configuration']->read('app/debug', false);
    }

    private function initializeUrlGeneratorProvider(): void
    {
        $this->register(new RoutingServiceProvider());
    }

    protected function registerProviders(): void
    {
    }

    protected function initializeServices(): void
    {
    }

    abstract protected function mountControllerProviders(): void;
}
