<?php

declare(strict_types = 1);

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

    public function minSize(int $value): self
    {
        $this->minSize = (int) $value;

        return $this;
    }

    public function maxSize(int $value): self
    {
        $this->maxSize = (int) $value;

        return $this;
    }

    public function getType(): int
    {
        return FieldTypes::STRING;
    }
}
