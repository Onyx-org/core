<?php

declare(strict_types = 1);

namespace Onyx;

use Puzzle\Configuration;

interface Plugin
{
    /**
     * Plugin name (for display and debug purposes)
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Will be less prioritary than application configuration
     *
     * @return Configuration
     */
    public function getConfiguration(): ?Configuration;

    /**
     * from the least prioritary directory to the most prioritary one
     *
     * @return iterable
     */
    public function getViewDirectories(): iterable;

    /**
     * from the least prioritary directory to the most prioritary one
     *
     * @return iterable
     */
    public function getOverrideViewDirectories(): iterable;

    /**
     * Will be loaded at bootstrap
     *
     * @return iterable
     */
    public function getProviders(): iterable;


    /**
     * Will be loaded when loadConsole method of plugin manager will be called
     *
     * @return iterable
     */
    public function getConsoleCommands(): iterable;
}
