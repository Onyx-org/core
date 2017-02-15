<?php

namespace Onyx\Persistence\Fields;

use Onyx\Persistence\Exceptions\InvalidDataException;
use Onyx\Persistence\Field;

class NotNullable implements Field
{
    private
        $field,
        $emptyAsNull;

    public function __construct(Field $field)
    {
        $this->field = $field;
        $this->emptyAsNull = false;
    }

    public function emptyAsNull()
    {
        $this->emptyAsNull = true;

        return $this;
    }

    private function checkNull($value)
    {
        if($value === null || ($this->emptyAsNull && $value === ""))
        {
            $message = sprintf("Value of %s can't be null", $this->getPrintableNamePath());
            throw new InvalidDataException($message);
        }
    }

    public function convert($value)
    {
        $this->checkNull($value);

        return $this->field->convert($value);
    }

    public function getNamePath()
    {
        return $this->field->getNamePath();
    }

    public function getPrintableNamePath()
    {
        return $this->field->getPrintableNamePath();
    }
    
    public function getType()
    {
        return $this->field->getType();
    }
}
