<?php

declare(strict_types = 1);

namespace Onyx;

use Puzzle\Configuration;
use Puzzle\ConfigurationSystem;
use Pimple\ServiceProviderInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Psr\Log\LoggerAwareInterface;
use Symfony\Component\Console\Command\Command;

class PluginManager implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private
        $configuration,
        $viewManager,
        $serviceContainer;

    public function __construct(Configuration $configuration, ?ViewManager $viewManager, ServiceContainer $serviceContainer)
    {
        $this->configuration = $configuration;
        $this->viewManager = $viewManager;
        $this->serviceContainer = $serviceContainer;
        $this->logger = new NullLogger();
    }

    public function load(): void
    {
        $plugins = $this->retrievePlugins();

        foreach($plugins as $plugin)
        {
            $this->loadPlugin($plugin);
        }

        foreach(array_reverse($plugins) as $plugin)
        {
            $this->loadPluginOverrideViews($plugin);
        }
    }

    private function loadPlugin(Plugin $plugin): void
    {
        $this->logger->info('Loading plugin ' . $plugin->getName());

        $this->loadPluginConfiguration($plugin);
        $this->loadPluginViews($plugin);
        $this->loadPluginProviders($plugin);
        $this->loadPluginControllers($plugin);
    }

    private function loadPluginConfiguration(Plugin $plugin): void
    {
        if($this->configuration instanceof ConfigurationSystem)
        {
            $pluginConfiguration = $plugin->getConfiguration();

            if($pluginConfiguration instanceof Configuration)
            {
                $this->logger->debug(sprintf(
                    '[%s] Loading configuration',
                    $plugin->getName()
                ));

                $this->configuration->addBase($pluginConfiguration);
            }
        }
    }

    private function loadPluginViews(Plugin $plugin): void
    {
        if($this->viewManager instanceof ViewManager)
        {
            $directories = $plugin->getViewDirectories();

            if(! empty($directories))
            {
                $this->logger->debug(sprintf(
                    '[%s] Loading views directories (%d)',
                    $plugin->getName(),
                    count($directories)
                ));

                $this->viewManager->addPath($directories, false);
            }
        }
    }

    private function loadPluginOverrideViews(Plugin $plugin): void
    {
        if($this->viewManager instanceof ViewManager)
        {
            $directories = $plugin->getOverrideViewDirectories();

            if(! empty($directories))
            {
                $this->logger->debug(sprintf(
                    '[%s] Loading override views directories (%d)',
                    $plugin->getName(),
                    count($directories)
                ));

                $this->viewManager->addPath($directories, true);
            }
        }
    }

    private function loadPluginProviders(Plugin $plugin): void
    {
        $providers = $plugin->getProviders();

        foreach($providers as $provider)
        {
            if($provider instanceof ServiceProviderInterface)
            {
                $this->logger->debug(sprintf(
                    '[%s] Loading provider %s',
                    $plugin->getName(),
                    get_class($provider)
                ));

                $this->serviceContainer->register($provider);
            }
        }
    }

    private function loadPluginControllers(Plugin $plugin): void
    {
        $declaration = $plugin->getControllers();

        if($declaration instanceof ControllersDeclaration)
        {
            foreach($declaration->getMountPoints() as list($prefix, $controllerProvider))
            {
                $this->logger->debug(sprintf(
                    '[%s] Mounting controller',
                    $plugin->getName()
                ), ['prefix' => $prefix]);

                $this->serviceContainer->mount($prefix, $controllerProvider);
            }
        }
    }

    private function retrievePlugins(): array
    {
        $plugins = [];

        foreach($this->retrieveEnabledPluginNames() as $pluginName)
        {
            $plugin = $this->instanciatePlugin($pluginName);

            if($plugin !== null)
            {
                $plugins[] = $plugin;
            }
        }

        return $plugins;
    }

    private function retrieveEnabledPluginNames(): iterable
    {
        $names = $this->configuration->read('plugins/enabled', []);

        if(! is_iterable($names))
        {
            throw new \InvalidArgumentException("Wrong configuration value for plugins/enabled : iterable expected");
        }

        return $names;
    }

    private function instanciatePlugin(string $pluginName): ?Plugin
    {
        if(class_exists($pluginName))
        {
            $plugin = new $pluginName;

            if($plugin instanceof Plugin)
            {
                return $plugin;
            }
        }

        $this->logger->error("Plugin $pluginName was not enabled");

        return null;
    }

    public function loadConsole(CommandContainer $commandContainer): void
    {
        $plugins = $this->retrievePlugins();

        foreach($plugins as $plugin)
        {
            $this->loadConsoleCommands($commandContainer, $plugin);
        }
    }

    private function loadConsoleCommands(CommandContainer $commandContainer, Plugin $plugin): void
    {
        foreach($plugin->getConsoleCommands() as $commandDefinition)
        {
            if(! $commandDefinition instanceof \Closure)
            {
                throw new \LogicException("Plugin has to return closures when getConsoleCommands is called");
            }

            $command = $commandDefinition($this->serviceContainer);

            if(! $command instanceof Command)
            {
                throw new \LogicException("Command definition closure has to return Command instance");
            }

            $commandContainer->add($command);
        }
    }
}
