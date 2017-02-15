<?php

namespace Onyx\Persistence\Fields;

use Onyx\Persistence\Field;
use Onyx\Persistence\FieldTypes;

class RawField implements Field
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

    public function getNamePath()
    {
        return $this->namePath;
    }

    public function convert($value)
    {
        return $value;
    }

    public function getPrintableNamePath()
    {
        return '[' . join('][', $this->namePath) . ']';
    }
    
    public function getType()
    {
        return FieldTypes::RAW;
    }
}
