<?php

declare(strict_types = 1);

namespace Onyx\Plugins;

use Puzzle\Pieces\ConvertibleToString;
use Onyx\Domain\ValueObject;

final class ViewExtensionInContainer implements ValueObject, ConvertibleToString
{
    private
        $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function key(): string
    {
        return $this->key;
    }

    public function __toString(): string
    {
        return $this->key;
    }
}
