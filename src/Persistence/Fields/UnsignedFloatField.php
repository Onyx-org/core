<?php

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

    public function setMin($value)
    {
        if(is_float($value) && $value < 0)
        {
            $value = (float) 0;
        }

        return parent::setMin($value);
    }
    
    public function getType()
    {
        return FieldTypes::FLOAT;
    }
}
