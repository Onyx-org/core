<?php

declare(strict_types = 1);

namespace Onyx\Plugins;

use Onyx\Plugin;
use Puzzle\Configuration;

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

    public function getConsoleCommands(): iterable
    {
        return [];
    }
}
