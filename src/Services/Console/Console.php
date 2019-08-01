<?php

declare(strict_types = 1);

namespace Onyx\Services\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface Console
{
    public static function configure(Command $command): void;

    public function execute(InputInterface $input, OutputInterface $output): void;
}
