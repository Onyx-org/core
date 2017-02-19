<?php

namespace Onyx;

use PHPUnit\Framework\TestCase;
use Puzzle\Configuration\Memory;
use Pimple\ServiceProviderInterface;
use Onyx\Plugins\AbstractPlugin;
use Puzzle\Configuration;
use Puzzle\Configuration\Stacked;
use Pimple\Container;

class Pony extends AbstractPlugin
{
    public function getName(): string { return "Pony"; }

    public function getConfiguration(): ?Configuration
    {
        return new Memory(['pony' => 'cloud', 'shared' => 'pony', 'global' => -1]);
    }

    public function getViewDirectories(): iterable { return ['pony_view_1', 'pony_view_2', 'pony_view_3']; }
    public function getOverrideViewDirectories(): iterable { return ['pony_override']; }

    public function getProviders(): iterable
    {
        return [
            new class implements ServiceProviderInterface {
                public function register(Container $pimple){}
            }
        ];
    }
}

class Unicorn extends AbstractPlugin
{
    public function getName(): string { return "Unicorn"; }

    public function getConfiguration(): ?Configuration
    {
        return new Memory(['poop' => 'rainbow', 'shared'  => 'unicorn', 'global' => -2]);
    }

    public function getOverrideViewDirectories(): iterable { return ['unicorn_override_1', 'unicorn_override_2']; }

    public function getProviders(): iterable
    {
        return [
            /* not a valid service container */ new \stdClass(),
            /* a valid one */ new class implements ServiceProviderInterface {
                public function register(Container $pimple){}
            }
        ];
    }
}

class PluginManagerTest extends TestCase
{
    private
        $configuration,
        $stackedConfiguration,
        $viewManager,
        $serviceContainer;

    protected function setUp()
    {
        $this->configuration = new Memory([
            'plugins/enabled' => [
                'Onyx\Pony',
                'Not\An\Existing\Class',
                'Onyx\Unicorn',
                'stdClass',
                'Onyx\PluginManagerTest', // not a plugin
            ],
            'global' => 42
        ]);

        $this->stackedConfiguration = new Stacked();
        $this->stackedConfiguration->overrideBy($this->configuration);

        $this->viewManager = new class implements ViewManager {
            public $count = 0;
            public function addPath($paths, bool $prioritary = true): void
            {
                $this->count += count($paths);
            }
        };

        $this->serviceContainer = new class implements ServiceContainer {
            public $count = 0;
            public function register(ServiceProviderInterface $provider, array $values = array())
            {
                $this->count++;
            }
        };

    }

    public function testLoad()
    {
        $manager = new PluginManager($this->stackedConfiguration, $this->viewManager, $this->serviceContainer);
        $manager->load();

        $this->assertConfiguration('cloud', 'pony');
        $this->assertConfiguration('rainbow', 'poop');
        $this->assertConfiguration('pony', 'shared');
        $this->assertConfiguration(42, 'global');

        $this->assertSame(6, $this->viewManager->count);
        $this->assertSame(2, $this->serviceContainer->count);
    }

    /**
     * @expectedException \Puzzle\Configuration\Exceptions\NotFound
     */
    public function testLoadWithNoConfigurationSystem()
    {
        $manager = new PluginManager($this->configuration, $this->viewManager, $this->serviceContainer);
        $manager->load();

        $this->assertConfiguration('cloud', 'pony');
    }

    private function assertConfiguration($expected, $key)
    {
        return $this->assertSame($expected, $this->stackedConfiguration->readRequired($key));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLoadWithInvalidConfiguration()
    {
        $configuration = new Memory([
            'plugins/enabled' => 39,
            'global' => 42
        ]);

        $manager = new PluginManager($configuration, $this->viewManager, $this->serviceContainer);
        $manager->load();
    }
}
