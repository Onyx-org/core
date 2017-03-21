<?php

declare(strict_types = 1);

namespace Onyx\Providers;

use Pimple\ServiceProviderInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\IntrospectionProcessor;
use Silex\Provider\MonologServiceProvider;
use Monolog\Handler\HandlerInterface;
use Onyx\Logger\Monolog\HandlerCollector;
use Puzzle\Pieces\PathManipulation;
use Pimple\Container;

class Monolog implements ServiceProviderInterface
{
    use PathManipulation;

    private
        $loggerDefinitions;

    public function __construct(array $loggerDefinitions = [])
    {
        $this->loggerDefinitions = $this->normalizeLoggerDefinitions($loggerDefinitions);
    }

    private function normalizeLoggerDefinitions(array $userDefinitions): array
    {
        $definitions = array();

        foreach($userDefinitions as $name => $userDefinition)
        {
            list($name, $userDefinition) = $this->normalizeLoggerDefinition($name, $userDefinition);
            $definitions[$name] = $userDefinition;
        }

        return $definitions;
    }

    private function normalizeLoggerDefinition($name, $definition): array
    {
        if(is_string($definition))
        {
            // 'name' is transformed into 'name' => array()
            $name = $definition;
            $definition = [];
        }

        if(! is_array($definition))
        {
            $definition = [];
        }

        return [$name, $definition];
    }

    public function register(Container $container)
    {
        $container->register(new MonologServiceProvider());

        $this->registerDefaultOptions($container);
        $this->registerDefaultLoggers();
        $this->registerLoggers($container);
    }

    private function registerDefaultOptions(Container $container): void
    {
        $container['logger.kernel.file']  = function ($c) {
            return $c['configuration']->read('logger/kernel/file', 'kernel.log');
        };
        $container['logger.kernel.level'] = function ($c) {
            return $c['configuration']->read('logger/kernel/level', Logger::INFO);
        };
        $container['logger.kernel.channel'] = function ($c) {
            return $c['configuration']->read('logger/kernel/channel', 'kernel');
        };

        // for compliance with debug toolbar
        $container['monolog.logfile'] = function ($c) {
            return $c['logger.directory.path'] . $c['logger.kernel.file'];
        };
        $container['monolog.level'] = function ($c) {
            return Logger::toMonologLevel($c['logger.kernel.level']);
        };
        $container['monolog.name'] = function ($c) {
            return $c['logger.kernel.channel'];
        };

        $container['logger.directory'] = 'logs';
        $container['logger.directory.path'] = function() use ($container) {
            $path = $this->computePath($container['var.path'], $container['logger.directory']);
            $this->ensureDirectoryExists($path);

            return $path;
        };

        $container['logger.global.handlers'] = function () {
            return new HandlerCollector();
        };
    }

    private function registerDefaultLoggers(): void
    {
        $defaultLoggerDefinitions = array(
            'plugins', 'app'
        );

        foreach($defaultLoggerDefinitions as $defaultLoggerName => $defaultLoggerDefinition)
        {
            list($defaultLoggerName, $defaultLoggerDefinition) = $this->normalizeLoggerDefinition($defaultLoggerName, $defaultLoggerDefinition);

            if(! isset($this->loggerDefinitions[$defaultLoggerName]))
            {
                $this->loggerDefinitions[$defaultLoggerName] = $defaultLoggerDefinition;
            }
        }
    }

    private function computePath(string $cleanPath, string $directoryToAppend): string
    {
        return $cleanPath . $this->removeWrappingSlashes($directoryToAppend) . DIRECTORY_SEPARATOR;
    }

    private function registerLoggers(Container $container): void
    {
        foreach($this->loggerDefinitions as $key => $definition)
        {
            $options = array(
                'file'    => $container['configuration']->read("logger/$key/file", "$key.log"),
                'level'   => $container['configuration']->read("logger/$key/level", Logger::INFO),
                'channel' => $container['configuration']->read("logger/$key/channel", $key),
                'allowGlobalHandlers' => true,
                'handlers.allowIntrospection' => false,
                'handlers' => array(),
                'disableDefaultHandler' => false,
            );

            $options = array_replace($options, $definition);

            $serviceId = "logger.$key";
            $container["$serviceId.level"] = $options['level'];
            $container["$serviceId.file"] = $options['file'];
            $container["$serviceId.channel"] = $options['channel'];

            $container[$serviceId] = function ($c) use($options) {
                $logger = new $c['monolog.logger.class']($options['channel']);

                if($options['disableDefaultHandler'] !== true)
                {
                    $handler = new StreamHandler(
                        $this->computeLogFilePath($c, $options),
                        $options['level']
                    );

                    $logger->pushHandler($handler);

                    if($options['handlers.allowIntrospection'] === true)
                    {
                        $this->addIntrospection($c, $handler);
                    }
                }

                $this->pushHandlers($c, $logger, $options);
                $this->pushGlobalHandlers($c, $logger, $options);

                return $logger;
            };
        }
    }

    private function computeLogFilePath(Container $container, array $options): string
    {
        $path = $container['logger.directory.path'];

        return $path . $options['file'];
    }

    private function pushHandlers(Container $container, \Monolog\Logger $logger, array $options): void
    {
        $handlers = $this->getHandlers($container, $options);

        if(! is_array($handlers))
        {
            $handlers = array($handlers);
        }

        foreach($handlers as $handler)
        {
            if($options['handlers.allowIntrospection'] === true)
            {
                $this->addIntrospection($container, $handler);
            }

            $logger->pushHandler($handler);
        }
    }

    private function getHandlers(Container $container, array $options)
    {
        $handlers = $options['handlers'];

        if(is_callable($options['handlers']))
        {
            $handlers = $options['handlers']($container);
        }

        return $handlers;
    }

    private function addIntrospection(Container $container, HandlerInterface $handler): void
    {
        $introspectionEnabled = $container['configuration']->read('logger/options/introspection/enabled', false);

        if($introspectionEnabled === true)
        {
            $introspectionLevel = $container['configuration']->read('logger/options/introspection/level', Logger::ERROR);
            $handler->pushProcessor(new IntrospectionProcessor($introspectionLevel));
        }
    }

    private function pushGlobalHandlers(Container $container, \Monolog\Logger $logger, array $options): bool
    {
        if($options['allowGlobalHandlers'] === false)
        {
            return false;
        }

        $collector = $container['logger.global.handlers'];

        if($collector instanceof MonologHandlerCollector)
        {
            foreach($collector->getHandlers() as $handler)
            {
                $logger->pushHandler($handler);
            }
        }

        return true;
    }

    private function ensureDirectoryExists($directory)
    {
        if(! is_dir($directory))
        {
            mkdir($directory, 0755, true);
        }
    }
}
