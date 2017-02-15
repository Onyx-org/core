<?php

namespace Onyx\Persistence\Fields;

use Onyx\Persistence\Field;
use Onyx\Persistence\Exceptions\InvalidDataException;
use Onyx\Persistence\FieldTypes;

class StringField extends Raw implements Field
{
    private
        $minSize,
        $maxSize;

    public function convert($value)
    {
        if($value === null)
        {
            return null;
        }

        if(! is_string($value))
        {
            $printValue = "";
            if(is_numeric($value))
            {
                $printValue = " = " . $value;
            }

            $message = sprintf('Value %s%s is not a valid string.', $this->getPrintableNamePath(), $printValue);
            throw new InvalidDataException($message);
        }

        if(isset($this->minSize) && strlen($value) < $this->minSize)
        {
            $message = sprintf('Value %s = "%s" is too short. Min length is : %s.', $this->getPrintableNamePath(), $value, $this->minSize);
            throw new InvalidDataException($message);
        }

        if(isset($this->maxSize) && strlen($value) > $this->maxSize)
        {
            $message = sprintf('Value %s = "%s" is too long. Max length is : %s.', $this->getPrintableNamePath(), $value, $this->maxSize);
            throw new InvalidDataException($message);
        }

        return $value;
    }

    public function minSize($value)
    {
        $this->minSize = $value;

        return $this;
    }

    public function maxSize($value)
    {
        $this->maxSize = $value;

        return $this;
    }
    
    public function getType()
    {
        return FieldTypes::STRING;
    }
}
