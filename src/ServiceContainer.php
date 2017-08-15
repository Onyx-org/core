<?php

namespace Onyx;

use Pimple\ServiceProviderInterface;

interface ServiceContainer
{
    /**
     * Registers a service provider.
     *
     * @param ServiceProviderInterface $provider A ServiceProviderInterface instance
     * @param array                    $values   An array of values that customizes the provider
     *
     * @return static
     */
    public function register(ServiceProviderInterface $provider, array $values = array());

    /**
     * Mounts controllers under the given route prefix.
     *
     * @param string                                                    $prefix      The route prefix
     * @param ControllerCollection|callable|ControllerProviderInterface $controllers A ControllerCollection, a callable, or a ControllerProviderInterface instance
     *
     * @return Application
     *
     * @throws \LogicException
     */
    public function mount($prefix, $controllers);
}
