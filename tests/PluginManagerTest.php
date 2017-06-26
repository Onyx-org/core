<?php

namespace Onyx;

use PHPUnit\Framework\TestCase;
use Puzzle\Configuration\Memory;
use Pimple\ServiceProviderInterface;
use Onyx\Plugins\AbstractPlugin;
use Puzzle\Configuration;
use Puzzle\Configuration\Stacked;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
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

    public function getConsoleCommands(): iterable
    {
        return [
            function(ServiceContainer $c) {
                return new class extends Command {
                    protected function configure() { $this->setName('command-pony'); }
                    protected function execute(InputInterface $input, OutputInterface $output){}
                };
            },
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

    public function getConsoleCommands(): iterable
    {
        return [
            function(ServiceContainer $c) {
                return new class extends Command {
                    protected function configure() { $this->setName('command-unicorn-one'); }
                    protected function execute(InputInterface $input, OutputInterface $output){}
                };
            },
            function(ServiceContainer $c) {
                return new class extends Command {
                    protected function configure() { $this->setName('command-unicorn-two'); }
                    protected function execute(InputInterface $input, OutputInterface $output){}
                };
            },
        ];
    }
}

class EvilPony extends AbstractPlugin
{
    public function getName(): string { return "EvilPony"; }

    public function getConsoleCommands(): iterable
    {
        return [
            new class extends Command {
                protected function configure() { $this->setName('command-unicorn-one'); }
                protected function execute(InputInterface $input, OutputInterface $output){}
            },
        ];
    }
}

class EvilUnicorn extends AbstractPlugin
{
    public function getName(): string { return "EvilUnicorn"; }

    public function getConsoleCommands(): iterable
    {
        return [
            function(ServiceContainer $c) {
                return new \DateTime();
            },
        ];
    }
}

class PluginManagerTest extends TestCase
{
    private
        $configuration,
        $stackedConfiguration,
        $viewManager,
        $serviceContainer,
        $commandContainer;

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

        $this->commandContainer = new class implements CommandContainer {
            public $count = 0;
            public function add(Command $command)
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

    public function testLoadConsole()
    {
        $manager = new PluginManager($this->configuration, $this->viewManager, $this->serviceContainer);
        $manager->loadConsole($this->commandContainer);

        $this->assertSame(3, $this->commandContainer->count);
    }

    /**
     * @expectedException \LogicException
     * @dataProvider providerTestLoadConsoleWithInvalidCommandsDefinitions
     */
    public function testLoadConsoleWithInvalidCommandsDefinitions(Configuration $configuration)
    {
        $manager = new PluginManager($configuration, $this->viewManager, $this->serviceContainer);
        $manager->loadConsole($this->commandContainer);
    }

    public function providerTestLoadConsoleWithInvalidCommandsDefinitions()
    {
        return array(
            "Plugin not returning closures for commands definitions" => [
                new Memory([
                    'plugins/enabled' => [ 'Onyx\EvilPony' ],
                ]),
            ],
            "Plugin command definition not returning Command instance " => [
                new Memory([
                    'plugins/enabled' => [ 'Onyx\EvilUnicorn' ],
                ]),
            ],
        );
    }
}
