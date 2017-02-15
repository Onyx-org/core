<?php

declare(strict_types = 1);

namespace Onyx\Persistence\Fields;

use Onyx\Persistence\Field;
use Onyx\Persistence\Exceptions\InvalidDataException;
use Onyx\Persistence\FieldTypes;

class Boolean extends Raw implements Field
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

    private function tryConvertStringValue($value): ?bool
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

    private function tryConvertIntValue($value): ?bool
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

    private function triggerConvertException($value): void
    {
        $printValue = "";

        if(is_string($value) || is_numeric($value))
        {
            $printValue = ' = ' . (string) $value;
        }

        throw new InvalidDataException(sprintf(
            'Value %s %s is not a boolean value.',
            $this->getPrintableNamePath(),
            $printValue
        ));
    }

    public function allowStringValues(): self
    {
        $this->allowStringValues = true;

        return $this;
    }

    public function allowIntegerValues(): self
    {
        $this->allowIntegerValues = true;

        return $this;
    }

    public function getType(): int
    {
        return FieldTypes::BOOLEAN;
    }
}
