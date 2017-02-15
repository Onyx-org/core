<?php

declare(strict_types = 1);

namespace Onyx\Persistence\Fields;

use Onyx\Persistence\Field;
use Onyx\Persistence\FieldTypes;

class Raw implements Field
{
    private
        $namePath;

    public function __construct($namePath = array())
    {
        $this->namePath = $namePath;
        if(! is_array($namePath))
        {
            $this->namePath = array($namePath);
        }
    }

    public function getNamePath(): array
    {
        return $this->namePath;
    }

    public function convert($value)
    {
        return $value;
    }

    public function getPrintableNamePath(): string
    {
        return '[' . join('][', $this->namePath) . ']';
    }

    public function getType(): int
    {
        return FieldTypes::RAW;
    }
}
