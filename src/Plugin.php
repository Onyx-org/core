<?php

declare(strict_types = 1);

namespace Onyx;

use Puzzle\Configuration;

interface Plugin
{
    /**
     * Plugin name (for display and debug purposes)
     */
    public function getName(): string;

    /**
     * Will be less prioritary than application configuration
     */
    public function getConfiguration(): ?Configuration;

    /**
     * from the least prioritary directory to the most prioritary one
     */
    public function getViewDirectories(): iterable;

    /**
     * from the least prioritary directory to the most prioritary one
     */
    public function getOverrideViewDirectories(): iterable;

    /**
     * Will be loaded at bootstrap
     */
    public function getProviders(): iterable;

    /**
     * Will be loaded at bootstrap
     */
    public function getControllers(): ?ControllersDeclaration;

    /**
     * Will be loaded when loadConsole method of plugin manager will be called
     */
    public function getConsoleCommands(): iterable;
}
