<?php

namespace Onyx\Webpack;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Onyx\Webpack\Manifest;
use Onyx\Traits;

class WebpackServiceProvider implements ServiceProviderInterface
{
    use
        Traits\PathManipulation;

    public function register(Container $container)
    {
        $container['webpack.manifest.path'] = $this->enforceEndingSlash($container['root.path']) . 'www/assets/webpack-manifest.json';
        $container['webpack.chunk.manifest.path'] = $this->enforceEndingSlash($container['root.path']) . '/www/assets/chunk-manifest.json';

        $container['webpack.manifest'] = function($c) {
            return new Manifest($c['webpack.manifest.path'], $c['webpack.chunk.manifest.path']);
        };

        if($container->offsetExists('twig'))
        {
            $container['twig']->addExtension(new TwigExtension($this['webpack.manifest']));
        }
    }
}
