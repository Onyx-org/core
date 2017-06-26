<?php

declare(strict_types = 1);

namespace Onyx;

use Symfony\Component\Console\Command\Command;

interface CommandContainer
{
    /**
     * Adds a command object.
     *
     * If a command with the same name already exists, it will be overridden.
     * If the command is not enabled it will not be added.
     *
     * @param Command $command A Command object
     *
     * @return Command|null The registered command if enabled or null
     */
    public function add(Command $command);
}
