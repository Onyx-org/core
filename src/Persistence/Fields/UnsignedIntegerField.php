<?php

namespace Onyx\Persistence\Fields;

use Onyx\Persistence\Field;
use Onyx\Persistence\FieldTypes;

class UnsignedIntegerField extends IntegerField implements Field
{
    public function __construct($namePath = array())
    {
        parent::__construct($namePath);

        $this->setMin(0);
    }

    public function setMin($value)
    {
        if (is_integer($value) && $value < 0)
        {
            $value = 0;
        }

        return parent::setMin($value);
    }
    
    public function getType()
    {
        return FieldTypes::INTEGER;
    }
}
