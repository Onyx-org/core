<?php

declare(strict_types = 1);

namespace Onyx\Providers;

use Pimple\ServiceProviderInterface;
use Puzzle\Configuration;
use Pimple\Container;
use Silex\Provider\DoctrineServiceProvider;
use Puzzle\PrefixedConfiguration;

class DBAL implements ServiceProviderInterface
{
    public function register(Container $app): void
    {
        $this->validatePuzzleConfiguration($app);
        $this->registerDatabases($app);
    }

    private function registerDatabases(Container $container): void
    {
        $options = array();
        $configuration = $container['configuration'];

        $databases = array_keys($configuration->readRequired('db'));
        foreach($databases as $database)
        {
            $options[$database] = $this->registerDatabase($container, $configuration, $database);
        }

        $container->register(new DoctrineServiceProvider(), array(
            'dbs.options' => $options
        ));
    }

    private function registerDatabase(Container $container, Configuration $configuration, string $database): array
    {
        $configuration = new PrefixedConfiguration($configuration, "db/$database");

        $options = array(
            'driver'   => 'pdo_mysql',
            'dbname'   => $configuration->readRequired('database'),
            'host'     => $configuration->readRequired('host'),
            'user'     => $configuration->readRequired('user'),
            'password' => $configuration->readRequired('password'),
            'port'     => $configuration->read('port', 3306),
            'charset'  => $configuration->read('charset', 'utf8'),
        );

        // Declare helper
        $container["db.$database"] = function () use ($container, $database) {
            return $container['dbs'][$database];
        };

        return $options;
    }

    private function validatePuzzleConfiguration(Container $container): void
    {
        if(! isset($container['configuration']) || ! $container['configuration'] instanceof Configuration)
        {
            throw new \LogicException(__CLASS__ . ' requires an instance of puzzle/configuration (as key "configuration")');
        }
    }
}
