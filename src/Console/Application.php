<?php

namespace Onyx\Console;

use Onyx\CommandContainer;
use Pimple\Container;

class Application extends \Symfony\Component\Console\Application implements CommandContainer
{
    private static $logo =
'     __
│   /  \ |\ | \ / \_/
│   \__/ | \|  |  / \
│
└─────────────────── ─ ─ ─ ─ ─ ─

';

    public function __construct(Container $container, $name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        parent::__construct($name, $version);

        $this->initializeConsolePlugins($container);
    }

    public function getHelp()
    {
        return self::$logo . parent::getHelp();
    }

    private function initializeConsolePlugins(Container $container): void
    {
        $container['plugin.manager']->loadConsole($this);
    }
}
