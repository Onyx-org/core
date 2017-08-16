<?php

declare(strict_types = 1);

namespace Onyx;

interface PluginManagerExtension
{
    public function loadCustomServices(array $plugins): void;
}
