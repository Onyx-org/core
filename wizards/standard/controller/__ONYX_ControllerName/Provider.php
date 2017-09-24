<?php

declare(strict_types = 1);

namespace __ONYX_Namespace\Controllers\__ONYX_BackOrFront\__ONYX_ControllerName;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

class Provider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $app['controller.__ONYX_BackOrFront_LC.__ONYX_ControllerName_LC'] = function() use($app) {
            $controller = new Controller();
            $controller
                ->setRequest($app['request_stack'])
                ->setTwig($app['twig']);

            return $controller;
        };

        $controllers = $app['controllers_factory'];

        $controllers
            ->match('/', 'controller.__ONYX_BackOrFront_LC.__ONYX_ControllerName_LC:homeAction')
            ->method('GET')
            ->bind('__ONYX_BackOrFront_LC.__ONYX_ControllerName_LC.home');

        return $controllers;
    }
}
