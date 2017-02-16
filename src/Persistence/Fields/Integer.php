<?php

declare(strict_types = 1);

namespace Onyx\Persistence\Fields;

use Onyx\Persistence\Field;
use Onyx\Persistence\Exceptions\InvalidDataException;
use Onyx\Persistence\FieldTypes;

class Integer extends Raw implements Field
{
    private
        $min,
        $max;

    public function convert($value)
    {
        if($value === null)
        {
            return null;
        }

        if(! is_integer($value))
        {
            $value = $this->tryToConvertValue($value);
        }

        $this->checkBounds($value);

        return $value;
    }

    private function tryToConvertValue($value): int
    {
        $intValue = null;

        if(is_string($value))
        {
            if(preg_match('/^-?\d+$/', $value) === 1)
            {
                $intValue = (int) $value;
            }
        }

        if(! is_integer($intValue))
        {
            $this->triggerException($value);
        }

        return $intValue;
    }

    private function checkBounds(?int $value): void
    {
        if(isset($this->min) && $value < $this->min)
        {
            throw new InvalidDataException(sprintf(
                'Value %s = %s is lower than minimum value : %s.',
                $this->getPrintableNamePath(),
                $value,
                $this->min
            ));
        }

        if(isset($this->max) && $value > $this->max)
        {
            throw new InvalidDataException(sprintf(
                'Value %s = %s is higher than maximum value : %s.',
                $this->getPrintableNamePath(),
                $value,
                $this->max
            ));
        }
    }

    private function triggerException($value): void
    {
        $printValue = "";

        if(is_string($value) || is_numeric($value))
        {
            $printValue = ' = ' . (string)$value;
        }

        throw new InvalidDataException(sprintf(
            'Value %s %s is not an integer value.',
            $this->getPrintableNamePath(),
            $printValue
        ));
    }

    public function setMin(int $value): self
    {
        $this->min = (int) $value;

        return $this;
    }

    public function setMax(int $value): self
    {
        $this->max = (int) $value;

        return $this;
    }

    public function getType(): int
    {
        return FieldTypes::INTEGER;
    }
}
