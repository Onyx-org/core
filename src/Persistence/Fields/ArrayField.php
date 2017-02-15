<?php

declare(strict_types = 1);

namespace Onyx\Persistence\Fields;

use Onyx\Persistence\Field;
use Onyx\Persistence\Exceptions\InvalidDataException;
use Onyx\Persistence\FieldTypes;

class ArrayField extends Raw implements Field
{
    private
        $itemField;

    public function __construct($namePath = array(), Field $itemField = null)
    {
        parent::__construct($namePath);

        $this->itemField = $itemField;
    }

    public function convert($value)
    {
        if($value === null)
        {
            return null;
        }

        if(! is_array($value))
        {
            $message = sprintf('Value of "%s" is not a valid array.', $this->getPrintableNamePath());

            throw new InvalidDataException($message);
        }

        $arrayValue = $this->convertFields($value);

        return $arrayValue;
    }

    private function convertFields(array $value)
    {
        if(isset($this->itemField))
        {
            $arrayValue = array();
            foreach($value as $fieldKey => $fieldValue)
            {
                $arrayValue[$fieldKey] = $this->itemField->convert($fieldValue);
            }

            return $arrayValue;
        }

        return $value;
    }

    public function getType(): int
    {
        return FieldTypes::ARRAY;
    }
}
