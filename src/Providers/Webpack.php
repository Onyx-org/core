<?php

namespace Onyx\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Puzzle\Pieces\PathManipulation;
use Onyx\Webpack\Manifest;
use Onyx\Webpack\TwigExtension;

class Webpack implements ServiceProviderInterface
{
    use PathManipulation;

    public function register(Container $container)
    {
        $container['webpack.manifest.path'] = $this->enforceEndingSlash($container['root.path']) . 'www/assets/webpack-manifest.json';
        $container['webpack.chunk.manifest.path'] = $this->enforceEndingSlash($container['root.path']) . '/www/assets/chunk-manifest.json';

        $container['webpack.manifest'] = function($c) {
            return new Manifest($c['webpack.manifest.path'], $c['webpack.chunk.manifest.path']);
        };

        if($container->offsetExists('twig'))
        {
            $container->extend('twig', function($twig) use ($container) {
                $twig->addExtension(new TwigExtension($container['webpack.manifest']));

                return $twig;
            });
        }
    }
}
