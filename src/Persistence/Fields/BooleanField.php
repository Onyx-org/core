<?php

namespace Onyx\Persistence\Fields;

use Onyx\Persistence\Field;
use Onyx\Persistence\Exceptions\InvalidDataException;
use Onyx\Persistence\FieldTypes;

class BooleanField extends RawField implements Field
{
    private
        $allowStringValues,
        $allowIntegerValues;

    public function __construct($namePath = array())
    {
        parent::__construct($namePath);

        $this->allowStringValues = false;
        $this->allowIntegerValues = false;
    }

    public function convert($value)
    {
        if(is_bool($value) || $value === null)
        {
            return $value;
        }

        $boolValue = null;

        if($this->allowStringValues)
        {
            $boolValue = $this->tryConvertStringValue($value);
        }

        if(! is_bool($boolValue) && $this->allowIntegerValues)
        {
            $boolValue = $this->tryConvertIntValue($value);
        }

        if(! is_bool($boolValue))
        {
            $this->triggerConvertException($value);
        }

        return (bool) $boolValue;
    }

    private function tryConvertStringValue($value)
    {
        $boolValue = null;
        if(is_string($value))
        {
            if(preg_match('/^[01]$/', $value) === 1)
            {
                $boolValue = (bool) $value;
            }
        }

        return $boolValue;
    }

    private function tryConvertIntValue($value)
    {
        $boolValue = null;
        if(is_integer($value))
        {
            if($value === 0 || $value === 1)
            {
                $boolValue = (bool) $value;
            }
        }

        return $boolValue;
    }

    private function triggerConvertException($value)
    {
        $printValue = "";

        if(is_string($value) || is_numeric($value))
        {
            $printValue = ' = ' . (string)$value;
        }

        throw new InvalidDataException(sprintf(
            'Value %s %s is not a boolean value.',
            $this->getPrintableNamePath(),
            $printValue
        ));
    }

    public function allowStringValues()
    {
        $this->allowStringValues = true;

        return $this;
    }

    public function allowIntegerValues()
    {
        $this->allowIntegerValues = true;

        return $this;
    }
    
    public function getType()
    {
        return FieldTypes::BOOLEAN;
    }
}
