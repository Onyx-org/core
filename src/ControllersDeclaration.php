<?php

declare(strict_types = 1);

namespace Onyx;

interface ControllersDeclaration
{
    /**
     * @return iterable<array> each array contains 2 entries : mounting point (string), provider (ControllerProviderInterface)
     *
     * Example :
     * return [
            ['/path', new X\Provider()],
            ['/path/to', new Y\Provider()],
            ['/path/to', new Z\Provider()],
        ];
     */
    public function getMountPoints(): iterable;
}
