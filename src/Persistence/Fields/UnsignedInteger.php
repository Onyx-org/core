<?php

declare(strict_types = 1);

namespace Onyx\Persistence\Fields;

use Onyx\Persistence\Field;
use Onyx\Persistence\FieldTypes;

class UnsignedInteger extends Integer implements Field
{
    public function __construct($namePath = array())
    {
        parent::__construct($namePath);

        $this->setMin(0);
    }

    public function setMin(int $value): Integer
    {
        if (is_integer($value) && $value < 0)
        {
            $value = 0;
        }

        return parent::setMin($value);
    }

    public function getType(): int
    {
        return FieldTypes::INTEGER;
    }
}
