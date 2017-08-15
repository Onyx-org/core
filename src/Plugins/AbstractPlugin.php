<?php

declare(strict_types = 1);

namespace Onyx\Plugins;

use Onyx\Plugin;
use Puzzle\Configuration;
use Onyx\ControllersDeclaration;

abstract class AbstractPlugin implements Plugin
{
    public function getConfiguration(): ?Configuration
    {
        return null;
    }

    public function getViewDirectories(): iterable
    {
        return [];
    }

    public function getOverrideViewDirectories(): iterable
    {
        return [];
    }

    public function getProviders(): iterable
    {
        return [];
    }

    public function getControllers(): ?ControllersDeclaration
    {
        return null;
    }

    public function getConsoleCommands(): iterable
    {
        return [];
    }
}
