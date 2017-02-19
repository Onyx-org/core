<?php

namespace Onyx;

interface ViewManager
{
    /**
     * Add directories to view environment
     *
     * @param string|array $paths sorted by priority (from the least one to the most one)
     * @param bool $prioritary true if added paths are more prioritary than existing ones, false else.
     */
    public function addPath($paths, bool $prioritary = true): void;
}
