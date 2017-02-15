<?php

declare(strict_types = 1);

namespace Onyx\Persistence\Fields;

use Onyx\Persistence\Field;
use Onyx\Persistence\FieldTypes;

class UnsignedFloatField extends FloatField implements Field
{
    public function __construct($namePath = array())
    {
        parent::__construct($namePath);

        $this->setMin(0);
    }

    public function setMin(float $value): FloatField
    {
        if(is_float($value) && $value < 0)
        {
            $value = (float) 0;
        }

        return parent::setMin($value);
    }

    public function getType(): int
    {
        return FieldTypes::FLOAT;
    }
}
