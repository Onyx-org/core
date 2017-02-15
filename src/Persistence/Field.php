<?php

namespace Onyx\Persistence;

interface Field
{
    public function getNamePath(): array;

    public function convert($value);

    public function getPrintableNamePath(): string;

    public function getType(): int;
}
