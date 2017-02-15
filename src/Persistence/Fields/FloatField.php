<?php

declare(strict_types = 1);

namespace Onyx\Persistence\Fields;

use Onyx\Persistence\Field;
use Onyx\Persistence\Exceptions\InvalidDataException;
use Onyx\Persistence\FieldTypes;

class FloatField extends Raw implements Field
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

        if(! is_float($value))
        {
            $value = $this->tryToConvertValue($value);
        }

        $this->checkBounds($value);

        return $value;
    }

    private function tryToConvertValue($value): ?float
    {
        $floatValue = null;

        if(is_numeric($value))
        {
            if(preg_match('~^([-]?[0-9]*(\.[0-9]*)?)$~', (string) $value) === 1)
            {
                $floatValue = (float) $value;
            }
        }

        if(! is_float($floatValue))
        {
            $this->triggerException($value);
        }

        return $floatValue;
    }

    private function checkBounds(?float $value): void
    {
        if(isset($this->min) && $value < $this->min)
        {
            throw new InvalidDataException(sprintf(
                'Value %s = %f is lower than minimum value : %f.',
                $this->getPrintableNamePath(),
                $value,
                $this->min
            ));
        }

        if(isset($this->max) && $value > $this->max)
        {
            throw new InvalidDataException(sprintf(
                'Value %s = %f is higher than maximum value : %f.',
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
            'Value %s %s is not a float value.',
            $this->getPrintableNamePath(),
            $printValue
        ));
    }

    public function setMin(float $value): self
    {
        $this->min = (float) $value;

        return $this;
    }

    public function setMax(float $value): self
    {
        $this->max = (float) $value;

        return $this;
    }

    public function getType(): int
    {
        return FieldTypes::FLOAT;
    }
}
