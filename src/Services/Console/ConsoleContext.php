<?php

declare(strict_types = 1);

namespace Onyx\Services\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ConsoleContext extends Command
{
    private
        $className,
        $executeClosure;

    public function __construct(string $className, \Closure $executeClosure)
    {
        self::assertValidClassname($className);
        $this->className = $className;
        $this->executeClosure = $executeClosure;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->className::configure($this);
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $closure = $this->executeClosure;

        $console = $closure();

        if (!$console instanceof $this->className)
        {
            throw new \LogicException(sprintf('Console is not an instance of "%s"', $this->className));
        }

        $console->execute($input, $output);
    }

    private function assertValidClassname(string $className)
    {
        if (! (new \ReflectionClass($className))->implementsInterface(Console::class))
        {
            throw new \LogicException(sprintf('Class "%s" does not implement Console', $className));
        }
    }
}
